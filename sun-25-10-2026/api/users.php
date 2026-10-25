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

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET") {
    $result = $userModel->getAllUsers();

    echo json_encode([
        "success" => true,
        "data" => $result
    ]);

    exit;
}

if ($method === "POST") {

    $input = json_decode(
        file_get_contents("php://input"),true
        );

    if (!is_array($input)) {
        http_response_code(400);

        echo json_encode([
            "success" => false,
            "message" => "Invalid JSON data."
        ]);

        exit;
    }

    $name = trim($input["name"] ?? "");
    $email = trim($input["email"] ?? "");
    $password = $input["password"] ?? "";

    if ($name === "" || $email === "" || $password === "") {
        http_response_code(422);

        echo json_encode([
            "success" => false,
            "message" => "Name, email, and password are required."
        ]);

        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(422);

        echo json_encode([
            "success" => false,
            "message" => "Please enter a valid email address."
        ]);

        exit;
    }

    if (strlen($password) < 8) {
        http_response_code(422);

        echo json_encode([
            "success" => false,
            "message" => "Password must be at least 8 characters."
        ]);

        exit;
    }


}


http_response_code(405);

echo json_encode([
    "success" => false,
    "message" => "Method not allowed."
]);
