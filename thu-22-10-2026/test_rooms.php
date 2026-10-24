<?php

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/classes/Room.php';

$database = new Database();
$pdo = $database->getConnection();

$roomModel = new Room($pdo);

$rooms = $roomModel->getAll();

echo "<pre>";
print_r($rooms);
echo "</pre>";
