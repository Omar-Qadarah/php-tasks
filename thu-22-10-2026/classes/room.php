<?php

class Room{
    private $pdo; 
    public function __construct($pdo){
        $this->pdo = $pdo;
    }
    public function getAll(){
    $stmt = $this->pdo->query("SELECT * FROM rooms");
    return $stmt->fetchAll();
    }

}