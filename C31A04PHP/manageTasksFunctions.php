<?php
    include_once("Task.php");

function getTasks(){
    global $taskFile;
    global $taskContent;
    global $tasks;

    if (file_exists($taskFile) and filesize($taskFile) > 0) {
        $tasksFileContent = file_get_contents($taskFile);
        $taskJSON = json_decode($tasksFileContent, TRUE);
        $taskContent = TRUE;

        foreach ($taskJSON as $task) {
            $taskObj = new Task($task['id'], $task['title'], $task['description']);
            $taskObj->setDateCreated($task['dateCreated']);
            $taskObj->setDateUpdated($task['dateUpdated']);
            $taskObj->setStatus($task['status']);
            $taskObj->setId($task['id']);
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
    global $dateUpdated;
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
        if($task->getId() == $updatedTask['id']){
            $task->setStatus($updatedTask['status']);
            $task->setTitle($updatedTask['title']);
            $task->setDescription($updatedTask['description']);
            $task->setDateUpdated($updatedTask['dateUpdated']);
        }
    }
    saveTasks();
}

function saveTasks(){
    global $tasks;
    global $taskFile;

    usort($tasks, array('Task', 'compareTasks'));
    $taskArray = [];
    foreach($tasks as $task){
        array_push($taskArray, json_encode($task->toArray(), JSON_PRETTY_PRINT) );
    }

    file_put_contents($taskFile, '[' . implode($taskArray, ",") . ']');
}

function createNewTask($newTask) {
    global $tasks;

        $task = new Task(1, $newTask['title'], $newTask['description']);
        $task->setDateCreated($newTask['dateCreated']);
        $task->setDateUpdated($newTask['dateCreated']);
        $task -> getNewID();
        array_push($tasks, $task);
        saveTasks();
}


function validateEditTask($newTask) : bool {
    global $errors, $editing;
    $isValid = TRUE;
    $fieldNames = array('title' => 'Title', 'description'=> 'Description', 'dateCreated'=> 'Date Created', 'dateUpdated'=>'Date Updated', 'status'=> 'Status');

    foreach($newTask as $field => $input){
        if(empty($input) && $field != 'id'){
            $errors[$field] = $fieldNames[$field] .' is empty';
            $editing = TRUE;
            $isValid = FALSE;
        }
    }

    $dateUpdated = date_parse_from_format('Ymd', $newTask['dateUpdated']);

    if(empty($errors['dateUpdated']) && (!$dateUpdated['month'] || !$dateUpdated['year'] || !$dateUpdated['day'])){
        $errors['dateUpdated'] = 'Dates must be in the format: YYYYMMDD';
        $editing = TRUE;
        $isValid = FALSE;
    } else if (empty($errors['dateUpdated']) && !checkdate($dateUpdated['month'], $dateUpdated['day'],$dateUpdated['year'])){
        $errors['dateUpdated'] = $newTask['dateUpdated'] . ' is not a valid date';
        $editing = TRUE;
        $isValid = FALSE;
    }

    if($isValid && $newTask['dateCreated'] > $newTask['dateUpdated']){
        $errors['dateUpdated'] = 'The date last updated must be greater than the created date';
        $editing = TRUE;
        $isValid = FALSE;
    }

    return $isValid;
}

function validateCreateTask($newTask) : bool{
    global $errors;
    $isValid = TRUE;
    $fieldNames = array('title' => 'Title', 'description'=> 'Description', 'dateCreated'=> 'Date Created', 'dateUpdated'=>'Date Updated', 'status'=> 'Status');

    foreach($newTask as $field => $input){
        if(empty($input) && $field != 'id' && $field != 'status'){
            $errors[$field] = $fieldNames[$field] .' is empty';
            $isValid = FALSE;
        }
    }

    $dateCreated = date_parse_from_format('Ymd', $newTask['dateCreated']);


    if(empty($errors['dateCreated']) && (!$dateCreated['month'] || !$dateCreated['year'] || !$dateCreated['day'])){
        $errors['dateCreated'] = 'Dates must be in the format: YYYYMMDD';
        $isValid = FALSE;
    } else if (empty($errors['dateCreated']) && !checkdate($dateCreated['month'], $dateCreated['day'], $dateCreated['year'])){
        $errors['dateCreated'] = $newTask['dateCreated'] . ' is not a valid date';
        $isValid = FALSE;
    }

    return $isValid;

}