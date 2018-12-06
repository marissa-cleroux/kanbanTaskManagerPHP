<?php
include_once("./manageTasksFunctions.php");
$tasks = [];

getTasks();

$status=$_GET["id"];

foreach($tasks as $task) {

    if ($task->getId() == $id) {
        $returnTask = json_encode($task->toArray(), JSON_PRETTY_PRINT);
    }
}


header("Content-Type: application/json");
header("Cache-Control: no-cache");
header("Content-Length: " . strlen($returnString));
echo $returnTask;

?>