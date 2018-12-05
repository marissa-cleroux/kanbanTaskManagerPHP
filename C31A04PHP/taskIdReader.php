<?php

function retrieveTaskId() : int  {
    $taskIdFile = 'TaskId.txt';
    if(file_exists($taskIdFile) and filesize($taskIdFile) > 0){
        $taskId = file_get_contents($taskIdFile);
        file_put_contents($taskIdFile, $taskId + 1);
        return $taskId;
    } else {
        $taskId = 10000;
        file_put_contents($taskIdFile, $taskId + 1);
        return $taskId;
    }
}