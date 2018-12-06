<?php
include_once("./manageTasksFunctions.php");
$tasks = [];

getTasks();

$status=$_GET["status"];

$statuses = array("todo" => 1, "indev"=> 2, "intest"=> 3, "complete"=> 4);

$returnTasks = "[";
foreach($tasks as $task) {

    if ($task->getStatus() == $status) {
        $returnTask = $task->toArray();
        unset($returnTask["dateCreated"]);
        unset($returnTask["description"]);
        $returnString .= json_encode($returnTask->toArray(), JSON_PRETTY_PRINT);

    }
}

$returnTasks .= "]";

header("Content-Type: application/json");
header("Cache-Control: no-cache");
header("Content-Length: " . strlen($returnString));
echo $returnTasks;

?>