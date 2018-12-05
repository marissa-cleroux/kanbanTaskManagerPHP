<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Tasks</title>
    <link rel="stylesheet" href="./styles/main.css" type="text/css" />

</head>
<body>
<header>
    <h1>Manage Tasks</h1>
</header>


<?php
$displayForm = TRUE;
$taskContent = FALSE;
$taskFile = "./Tasks/Tasks.json";
$tasks = [];
$editing = FALSE;

function getTasks(){
    global $taskFile;
    global $taskContent;
    global $tasks;

    if(file_exists($taskFile)){
        $tasksFileContent = file_get_contents($taskFile);
        echo '<pre>';
        echo $tasksFileContent;
        echo '</pre>';
        $tasks = json_decode($tasksFileContent, TRUE);
        $taskContent =TRUE;

        echo '<pre>';
        echo $tasks;
        echo '</pre>';

        foreach($tasks as $i1 => $task){
            echo '<pre>';
            echo $task;
            echo '</pre>';
            if(is_object($task)){

                foreach($task as $i2 => $prop){

                    echo $prop;
                }
            }
        }

    } else {
        echo 'File does not exist or is 0';
    }
}

getTasks();
if(isset($_POST['addContact'])){

    foreach($_POST as $index => $field){
        $_POST[$index] = htmlspecialchars_decode($field);
    }

    $addressString = implode(',', array_slice($_POST, 0, 4));
    array_unshift($addresses, $addressString);
    $addresses = array_unique($addresses);
    $addresses = array_values($addresses);

    natcasesort($addresses);

    if(!file_put_contents('addresses.txt', join("\n", $addresses))){
        echo '<h3>Your address book could not be written to at this time, try again later.</h3>';
    }
}
getTasks();


if($displayForm){

    ?>
    <main>
        <div id="addressBook">
            <?php
            if($taskContent) {
                echo $tasks;
            }

            else {
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

                    foreach($addresses as $contact){
                        $contact = explode(',', $contact);
                        echo '<tr>';
                        foreach($contact as $parameter){
                            echo "<td>$parameter</td>";
                        }
                        echo '</tr>';
                    }
                    ?>

                </table>

            <?php } ?>

            <form method="POST" action="manageTasks.php" enctype="multipart/form-data">
                <label>Title: </label>
                <input type="text" name="title" id="title">
                <label>Description: </label>
                <input type="text" name="description" id="description">
                <label>Status: </label>
                <input type="text" name="status" id="status" <?php echo ($editing)? "disabled" : ""?> >
                <label></label><input type="submit" value="Save Task" name="saveTask">
            </form>
        </div>
    </main>
    <?php

}
?>
</body>
</html>