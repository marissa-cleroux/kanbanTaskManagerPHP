<?php
    include_once("Task.php");

function getTasks(){
    global $taskFile;
    global $taskContent;
    global $tasks;

    if (file_exists($taskFile)) {
        $tasksFileContent = file_get_contents($taskFile);
        $taskJSON = json_decode($tasksFileContent, TRUE);
        $taskContent = TRUE;

        foreach ($taskJSON as $task) {
            $taskObj = new Task($task["id"], $task["title"], $task["description"]);
            $taskObj->setDateCreated($task["dateCreated"]);
            $taskObj->setDateUpdated($task["dateUpdated"]);
            $taskObj->setStatus($task["status"]);
            $taskObj->setId($task["id"]);
            array_push($tasks, $taskObj);
        }
    } else {
        echo 'File does not exist';
    }
}

function editTask($taskId, $tasks){
    global $editing;
    global $title;
    global $description;
    global $status;
    global $id;

    $editing = TRUE;

    foreach ($tasks as $task) {
        if ($task->getId() == $taskId) {
            $title = $task->getTitle();
            $description = $task->getDescription();
            $status = $task->getStatus();
            $id = $task->getId();
        }
    }
}

function updateTask($updatedTask){
    global $tasks;

    foreach($tasks as $task){
        echo 'LOOP TASK: '. $task->getId();
        echo '<br>';
        echo 'SEARCH TASK: ' . $updatedTask['id'];
        echo '<br>';
        if($task->getId() == $updatedTask['id']){
            echo 'TASK ID FOUND';
            $task->updateDateUpdated();
            $task->setStatus($updatedTask['status']);
            $task->setTitle($updatedTask['title']);
            $task->setDescription($updatedTask['description']);
        }
    }
    saveTasks();
}

function saveTasks(){
    global $tasks;
    global $taskFile;

    $taskArray = [];
    foreach($tasks as $task){
        array_push($taskArray, json_encode($task->toArray(), JSON_PRETTY_PRINT) );
    }

    file_put_contents($taskFile, '[' . implode($taskArray, ",") . ']');
}

function createNewTask($newTask) {
    global $tasks;
    $task = new Task(1, $newTask['title'], $newTask['description']);
    $task -> getNewID();
    array_push($tasks, $task);
    saveTasks();
}