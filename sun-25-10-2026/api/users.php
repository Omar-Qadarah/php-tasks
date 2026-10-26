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
    try {
    $created = $userModel->register($name, $email, $password);

    if ($created) {
        http_response_code(201);

        echo json_encode([
            "success" => true,
            "message" => "User created successfully."
        ]);

        exit;
    }else{

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to create user."
    ]);

    exit;
    }
    } catch (PDOException $e) {
        http_response_code(409);

        echo json_encode([
            "success" => false,
            "message" => "Could not create user. The email may already be registered."
        ]);

        exit;
    }

}

if ($method === "PUT") {

    $input = json_decode( file_get_contents("php://input"), true);

    if (!is_array($input)) {
        http_response_code(400);

        echo json_encode([
            "success" => false,
            "message" => "Invalid JSON data."
        ]);

        exit;
    }

    $id = filter_var( $input["id"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);

    $name = trim($input["name"] ?? "");
    $email = trim($input["email"] ?? "");

    $password = $input["password"] ?? "";

    if ($id === false || $id === null) {
        http_response_code(422);

        echo json_encode([
            "success" => false,
            "message" => "A valid user ID is required."
        ]);

        exit;
    }

    if ($name === "" || $email === "") {
        http_response_code(422);

        echo json_encode([
            "success" => false,
            "message" => "Name and email are required."
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

    if ($password !== "" && strlen($password) < 8) {
        http_response_code(422);

        echo json_encode([
            "success" => false,
            "message" => "Password must be at least 8 characters."
        ]);

        exit;
    }


    $existingUser = $userModel->getUserById($id);

    if (!$existingUser) {
        http_response_code(404);

        echo json_encode([
            "success" => false,
            "message" => "User not found."
        ]);

        exit;
    }

    try {
        $updated = $userModel->updateUser(
            $id,
            $name,
            $email,
            $password
        );

        if ($updated) {
            echo json_encode([
                "success" => true,
                "message" => "User updated successfully."
            ]);

            exit;
        }else{

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "Failed to update user."
        ]);

        exit;}

    } catch (PDOException $e) {

        if ($e->getCode() === "23000") {
            http_response_code(409);

            echo json_encode([
                "success" => false,
                "message" => "That email may already be registered."
            ]);

            exit;
        }

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "A database error occurred."
        ]);

        exit;
    }
}

if ($method === "DELETE") {

    $input = json_decode(
        file_get_contents("php://input"),
        true
    );

    if (!is_array($input)) {
        http_response_code(400);

        echo json_encode([
            "success" => false,
            "message" => "Invalid JSON data."
        ]);

        exit;
    }

    $id = filter_var( $input["id"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);

    if ($id === false || $id === null) {
        http_response_code(422);

        echo json_encode([
            "success" => false,
            "message" => "A valid user ID is required."
        ]);

        exit;
    }

    $existingUser = $userModel->getUserById($id);

    if (!$existingUser) {
        http_response_code(404);

        echo json_encode([
            "success" => false,
            "message" => "User not found."
        ]);

        exit;
    }

    $deleted = $userModel->deleteUser($id);

    if ($deleted) {
        echo json_encode([
            "success" => true,
            "message" => "User deleted successfully."
        ]);

        exit;
    }

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to delete user."
    ]);

    exit;
}


http_response_code(405);

echo json_encode([
    "success" => false,
    "message" => "Method not allowed."
]);
