<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Tasks</title>
    <link rel="stylesheet" href="./styles/main.css" type="text/css"/>

</head>
<body>
<header>
    <h1>Manage Tasks</h1>
</header>


<?php

include_once("Task.php");

$displayForm = TRUE;
$taskContent = FALSE;
$taskFile = "./Tasks/Tasks.json";
$tasks = [];
$editing = FALSE;
$title = "";
$description = "";
$status = "";
$id = "";

function getTasks()
{
    global $taskFile;
    global $taskContent;
    global $tasks;

    if (file_exists($taskFile)) {
        $tasksFileContent = file_get_contents($taskFile);
        $taskJSON = json_decode($tasksFileContent, TRUE);
        $taskContent = TRUE;

        foreach ($taskJSON as $i => $task) {
            $taskObj = new Task($task["title"], $task["description"]);
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

getTasks();

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
        array_push($taskArray, json_encode($task->toArray()) );
    }

    file_put_contents($taskFile, '[' . implode($taskArray, ",") . ']');
}

function createNewTask($newTask) {
    global $tasks;
    array_push($tasks, new Task($newTask['title'], $newTask['description']));
    saveTasks();


}

function validateTask(){

}

if (isset($_GET['deleteTask'])) {

}

if (isset($_GET['editTask'])) {
    editTask($_GET['editTask'], $tasks);
}

if (isset($_POST['saveTask'])) {
    if($_POST['id'] != ""){
        echo "Id does not equal nothing";
        updateTask($_POST);
    } else {
        echo "Id not set";
        createNewTask($_POST);
    }
}

?>
<main>
    <div id="currentTasks">
        <?php
        if (!$taskContent) {
            echo 'NO TASKS';
        } else {
            ?>

            <table>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date Created</th>
                    <th>Date Last Updated</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>

                <?php

                foreach ($tasks as $task) {
                    echo '<tr>';
                    echo '<td>';
                    echo $task->getTitle();
                    echo '</td>';
                    echo '<td>';
                    echo $task->getDescription();
                    echo '</td>';
                    echo '<td>';
                    echo $task->getDateCreated();
                    echo '</td>';
                    echo '<td>';
                    echo $task->getDateUpdated();
                    echo '</td>';
                    echo '<td>';
                    echo '<a href="?editTask=' . $task->getId() . '">edit</a>';
                    echo '</td>';
                    echo '<td>';
                    echo '<a href="?deleteTask=' . $task->getId() . '">delete</a>';
                    echo '</td>';
                    echo '<tr>';
                }
                ?>

            </table>

        <?php } ?>

        <form method="POST" action="manageTasks.php" enctype="multipart/form-data">
            <label>Title: </label>
            <input type="text" name="title" id="title" value="<?php echo $title ?>">
            <label>Description: </label>
            <input type="text" name="description" id="description" value="<?php echo $description ?>">
            <label>Status: </label>
            <select <?php echo ($editing) ? "" : "disabled" ?> name="status" id="status">
                <option value="0">--Status--</option>
                <option value="1"
                    <?php echo ($status == 1) ? "selected" : ""; ?>>To Do
                </option>
                <option value="2"
                    <?php echo ($status == 2) ? "selected" : ""; ?>>In Development
                </option>
                <option value="3"
                    <?php echo ($status == 3) ? "selected" : ""; ?>>In Test
                </option>
                <option value="4"
                    <?php echo ($status == 4) ? "selected" : ""; ?>>Complete
                </option>
            </select>
            <input type="hidden" name="id" id="id" value="<?php echo $id ?>">
            <label></label><input type="submit" value="Save Task" name="saveTask">
        </form>
    </div>
</main>
</body>
</html>