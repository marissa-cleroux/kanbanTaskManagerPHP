<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Tasks</title>
    <link rel="stylesheet" href="./styles/main.css" type="text/css"/>
    <link href="https://fonts.googleapis.com/css?family=Lora|Montserrat" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">


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
$status_class = array(1 =>"todo", 2=>"indev", 3=>"intest", 4=>"complete");


getTasks();

if (isset($_GET["deleteTask"])) {

}

if (isset($_GET["editTask"])) {
    editTask($_GET["editTask"], $tasks);
}

if (isset($_POST["saveTask"])) {

        if($_POST['id'] == "" && validateCreateTask($_POST)){
            createNewTask($_POST);
        } else if ($_POST['id'] != "" && validateEditTask($_POST)) {
            updateTask($_POST);
        } else {
            $title = $_POST["title"];
            $dateCreated = $_POST["dateCreated"];
            $dateUpdated = $_POST["dateUpdated"];
            $description = $_POST["description"];
            $status = $_POST["status"];
    }

}

getTasks();
?>
<main>
    <form method="POST" action="manageTasks.php" enctype="multipart/form-data">
        <label>Title: </label>
        <input type="text" name="title" id="title"
               value="<?php echo $title ?>"
               class="<?php echo (!empty($errors["title"]))?"error": "";?>"
               <?php echo ($status == 4)?"disabled": "";?>/>
        <p class="errorTxt"><?php echo (!empty($errors["title"]))?$errors["title"]: '';?></p>

        <label>Description: </label>
        <textarea name="description" id="description"
               rows="5"
               class="<?php echo (!empty($errors["description"]))?"error": "";?>"
               <?php echo ($status == 4)?"disabled": "";?>
        >
        <?php echo $description?>
        </textarea>
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
               class="<?php echo (!empty($errors["dateUpdated"]))?"error": "";?>"
               <?php echo ($status == 4)?"disabled": "";?>/>

        <p class="errorTxt <?php echo ($editing) ? "" : "hide" ?>"><?php echo (!empty($errors['dateUpdated']))?$errors['dateUpdated']: '';?></p>

        <label class="<?php echo ($editing) ? "" : "hide" ?>">Status: </label>
        <select class="<?php echo ($editing) ? "" : "hide";  (!empty($errors["status"]))?"error": "";?>"
                name="status"
                id="status">
            <option value="1"
                    class="<?php echo ($status == 1 || $status == 0)? ""  : "hide"?>"
                <?php echo ($status == 1) ? "selected" : ""; ?>>To Do
            </option>
            <option value="2"
                    class="<?php echo ($status == 1 || $status == 2 || $status == 3)? "" : "hide"?>"
                <?php echo ($status == 2) ? "selected" : ""; ?>>In Development
            </option>
            <option value="3"
                    class="<?php echo ($status == 2 || $status == 3)? "" : "hide"?>"
                <?php echo ($status == 3) ? "selected" : ""; ?>>In Test
            </option>
            <option value="4"
                    class="<?php echo ($status == 3)? "" : "hide"?>"
                <?php echo ($status == 4) ? "selected" : ""; ?>>Complete
            </option>
        </select>
        <p class="errorTxt <?php echo ($editing) ? "" : "hide" ?>"><?php echo (!empty($errors['status']))?$errors['status']: '';?></p>

        <input type="hidden" name="id" id="id" value="<?php echo $id ?>"/>
        <label></label><input type="submit" value="Save Task" name="saveTask"/>
    </form>

    <div id="currentTasks">
        <?php
        if (!$taskContent) {
            echo "There are currently no tasks";
        } else {
            ?>

                <?php

                foreach ($tasks as $task) {

                    echo "<div class='task {$status_class[$task->getStatus()]}'>";
                    echo "<a href='?editTask={$task->getId()}'>{$task->getTitle()}    <i class=\"fas fa-pencil-alt\"></i></a>";
                    echo "<p>{$task->getDescription()}</p>";
                    echo "<p class='dates'>Date Updated: {$task->getDateUpdated()}</p>";
                    echo "<p class='dates'>Date Created: {$task->getDateCreated()}</p>";
                    echo "</div>";
                }
                ?>
        <?php } ?>


    </div>
</main>
</body>
</html>