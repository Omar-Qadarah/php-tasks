<?php
session_start();

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized"
    ]);
    exit;
}
require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../user.php";

$database = new Database();
$pdo = $database->connect();
$userModel = new User($pdo);
$result = $userModel->getAllUsers();
echo json_encode([
    "success" => true,
    "data" => $result
]);