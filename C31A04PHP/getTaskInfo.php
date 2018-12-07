<?php
include_once("./manageTasksFunctions.php");
$tasks = [];
getTasks();

$status=$_GET["status"];

$statuses = array("todo" => 1, "indev"=> 2, "intest"=> 3, "complete"=> 4);

$returnTasks = "[";
foreach($tasks as $task) {
    echo $task->getStatus();
    echo $statuses[$status];
    if ($task->getStatus() == $statuses[$status]) {
        $returnTask = $task->toArray();
        unset($returnTask["dateCreated"]);
        unset($returnTask["description"]);
        $returnTasks .= json_encode($returnTask, JSON_PRETTY_PRINT);
    }
}

$returnTasks .= "]";

header("Content-Type: application/json");
header("Cache-Control: no-cache");
header("Content-Length: " . strlen($returnTasks));
echo $returnTasks;

?>