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
include_once("manageTasksFunctions.php");

$displayForm = TRUE;
$taskContent = FALSE;
$taskFile = "./Tasks/Tasks.json";
$tasks = [];
$editing = FALSE;
$title = "";
$description = "";
$status = "";
$id = "";



getTasks();

function validateTask(){

}

if (isset($_GET['deleteTask'])) {
    
}

if (isset($_GET['editTask'])) {
    editTask($_GET['editTask'], $tasks);
}

if (isset($_POST['saveTask'])) {
    if($_POST['id'] != ""){
        updateTask($_POST);
    } else {
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