<?php

require_once __DIR__ . '/config/Database.php';

try {
    $database = new Database();

    $pdo = $database->getConnection();

    echo "Database connected successfully!";
} catch (RuntimeException $e) {
    echo $e->getMessage();
}