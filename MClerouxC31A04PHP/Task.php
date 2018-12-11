<?php

include_once('taskIdReader.php');

class Task {
    private $id;
    private $title;
    private $description;
    private $dateCreated;
    private $dateUpdated;
    private $status;

    function __construct($id, $title, $desc){
        $this->id = $id;
        $this->title = $title;
        $this->description = $desc;
        $this->dateCreated = date("Ymd");
        $this->dateUpdated = date("Ymd");
        $this->status = 1;
    }

    public function setId($id): void{
        $this->id = $id;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getTitle(){
        return $this->title;
    }

    public function setTitle($title): void{
        $this->title = $title;
    }

    public function getDescription(){
        return $this->description;
    }

    public function setDescription($description): void{
        $this->description = $description;
    }

    public function getDateCreated(){
        return $this->dateCreated;
    }

    public function setDateCreated($dateCreated): void{
        $this->dateCreated = $dateCreated;
    }

    public function getDateUpdated(){
        return $this->dateUpdated;
    }

    public function setDateUpdated($dateUpdated): void{
        $this->dateUpdated = $dateUpdated;
    }

    public function getStatus(): int{
        return $this->status;
    }

    public function setStatus(int $status): void{
        $this->status = $status;
    }

    public function toArray() : Array {
        return array( "id"=>$this->id, "title"=>$this->title, "description"=>$this->description,
            "dateCreated"=>$this->dateCreated, "dateUpdated"=>$this->dateUpdated, "status"=>$this->status);
    }

    public function updateDateUpdated() {
        $this->dateUpdated = date('Ymd');
    }

    public function getNewID(){
        $this->id = retrieveTaskId();
    }

    static function compareTasks($task1, $task2){
        if($task1-> getDateUpdated() == $task2 ->getDateUpdated()){
            return 0;
        }

        return ($task1->getDateUpdated() < $task2 ->getDateUpdated()) ? 1 : -1;
    }
}
