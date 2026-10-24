<?php

class Database{
    private $host = "localhost";
    private $dbname = "study_rooms";
    private $username = "root";
    private $password = "";

    private $connection;

    public function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
        try{
            $this->connection = new PDO(
                $dsn,
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }catch (PDOException $e) {
            throw new RuntimeException(
                "Database connection failed.",
                0,
                $e
            );
        }
    }
    public function getConnection(){
        return $this->connection;
    }

    }
