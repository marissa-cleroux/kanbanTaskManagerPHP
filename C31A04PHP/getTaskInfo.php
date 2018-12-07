<?php
include_once("./manageTasksFunctions.php");
$tasks = [];
$returnTasksArray = array();
getTasks();

$status=$_GET["status"];

$statuses = array("todo" => 1, "indev"=> 2, "intest"=> 3, "complete"=> 4);

$returnTasks = "[";
foreach($tasks as $task) {
    if ($task->getStatus() == $statuses[$status]) {
        $returnTask = $task->toArray();
        unset($returnTask["dateCreated"]);
        unset($returnTask["description"]);
        array_push($returnTasksArray, json_encode($returnTask, JSON_PRETTY_PRINT));
    }
}

$returnTasks .= join($returnTasksArray, ",");

$returnTasks .= "]";

header("Content-Type: application/json");
header("Cache-Control: no-cache");
header("Content-Length: " . strlen($returnTasks));
echo $returnTasks;

?>