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
$dateCreated = date("Ymd");
$dateUpdated = date("Ymd");
$taskObj = "";
$errors = array("title"=>"", "description"=>"", "dateCreated"=>"", "dateUpdated"=>"", "status"=>"");


getTasks();

if (isset($_GET["deleteTask"])) {

}

if (isset($_GET["editTask"])) {
    editTask($_GET["editTask"], $tasks);
}

if (isset($_POST["saveTask"])) {
    //echo var_dump(date_parse_from_format("Ymd", $_POST['dateCreated']));

        if($_POST['id'] == "" && validateCreateTask($_POST)){
            echo "CREATING";
            createNewTask($_POST);
        } else if ($_POST['id'] != "" && validateEditTask($_POST)) {
            echo "UPDATING";
            updateTask($_POST);
        } else {
            $title = $_POST["title"];
            $dateCreated = $_POST["dateCreated"];
            $dateUpdated = $_POST["dateUpdated"];
            $description = $_POST["description"];
            $status = $_POST["status"];
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
            <input type="text" name="title" id="title"
                   value="<?php echo $title ?>"
                   class="<?php echo (!empty($errors["title"]))?"error": "";?>"/>
            <p class="errorTxt"><?php echo (!empty($errors["title"]))?$errors["title"]: '';?></p>

            <label>Description: </label>
            <input type="text" name="description" id="description"
                   value="<?php echo $description ?>"
                   class="<?php echo (!empty($errors["description"]))?"error": "";?>"/>
            <p class="errorTxt"><?php echo (!empty($errors["description"]))?$errors["description"]: '';?></p>

            <label class="<?php echo ($editing) ? "hide" : "" ?>">Date Created:</label>
            <input type="<?php echo ($editing) ? "hidden" : "text" ?>"
                   name="dateCreated" id="dateCreated"
                   value="<?php echo $dateCreated ?>"
                   class="<?php echo (!empty($errors["dateCreated"]))?"error": "";?>"/>
            <p class="errorTxt <?php echo ($editing) ? "hide" : "" ?>"><?php echo (!empty($errors['dateCreated']))?$errors['dateCreated']: '';?></p>

            <label class="<?php echo ($editing) ? "" : "hide" ?>">Date Updated:</label>
            <input type="<?php echo ($editing) ? "text" : "hidden" ?>"
                   name="dateUpdated"
                   id="dateUpdated"
                   value="<?php echo $dateUpdated ?>"
                   class="<?php echo (!empty($errors["dateUpdated"]))?"error": "";?>"/>

            <p class="errorTxt <?php echo ($editing) ? "" : "hide" ?>"><?php echo (!empty($errors['dateUpdated']))?$errors['dateUpdated']: '';?></p>

            <label class="<?php echo ($editing) ? "" : "hide" ?>">Status: </label>
            <select class="<?php echo ($editing) ? "" : "hide";  (!empty($errors["status"]))?"error": "";?>"
                    name="status"
                    id="status">
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
            <p class="errorTxt <?php echo ($editing) ? "" : "hide" ?>"><?php echo (!empty($errors['status']))?$errors['status']: '';?></p>

            <input type="hidden" name="id" id="id" value="<?php echo $id ?>"/>
            <label></label><input type="submit" value="Save Task" name="saveTask"/>
        </form>
    </div>
</main>
</body>
</html>