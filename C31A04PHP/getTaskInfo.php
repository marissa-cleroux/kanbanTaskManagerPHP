<?php
include_once("./manageTasksFunctions.php");
$tasks = [];
getTasks();

$status=$_GET["status"];

echo "YOU ARE HERE";

function getSingleStatus($stat) {
    global $tasks;
    $returnTasksArray = array();
    $statuses = array("todo" => 1, "indev"=> 2, "intest"=> 3, "complete"=> 4);

    foreach($tasks as $task) {
        if ($task->getStatus() == $statuses[$stat]) {
            $returnTask = $task->toArray();
            unset($returnTask["dateCreated"]);
            unset($returnTask["description"]);
            array_push($returnTasksArray, json_encode($returnTask, JSON_PRETTY_PRINT));
        }
    }

    return $returnTasksArray;
}

/*function getAllTasks(){
    global $tasks;
    $returnTasksArray = array();

    foreach($tasks as $task) {
        $returnTask = $task->toArray();
        unset($returnTask["dateCreated"]);
        unset($returnTask["description"]);
        array_push($returnTasksArray, json_encode($returnTask, JSON_PRETTY_PRINT));
    }

    return $returnTasksArray;
}*/

$returnTasks = "[";

if($status != "all"){
    $returnTasksArray  = getSingleStatus($status);
} else {
    $returnTasksArray = getAllTasks();
}

$returnTasks .= join($returnTasksArray, ",");

$returnTasks .= "]";

header("Content-Type: application/json");
header("Cache-Control: no-cache");
header("Content-Length: " . strlen($returnTasks));
echo $returnTasks;

?>