<?php
    class Student{
        private $pdo;
        public function __construct($pdo){
            $this->pdo = $pdo;
        }
        public function emailExists($email){
        $stmt = $this->pdo->prepare("SELECT id FROM students WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
        }
        public function register($firstName, $lastName, $email, $password){
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("INSERT INTO students (first_name,last_name,email,password_hash) VALUES (?, ?, ?, ?)");
            $stmt->execute([$firstName, $lastName, $email, $passwordHash]);
        }
    }