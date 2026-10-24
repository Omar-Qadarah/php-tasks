
<?php
session_start();

require_once __DIR__ . "/config/Database.php";
require_once __DIR__ . "/classes/Student.php";

$database = new Database();
$pdo = $database->getConnection();

$student = new Student($pdo);

$errors = [];
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if ($password === "") {
        $errors[] = "Please enter your password.";
    }

    if (empty($errors)) {
        try {
            $studentData = $student->findByEmail($email);

            if ($studentData && password_verify($password, $studentData["password_hash"])
            ) {
                session_regenerate_id(true);
                $_SESSION["student_id"] = (int) $studentData["id"];
                $_SESSION["student_name"] = $studentData["first_name"] . " " . $studentData["last_name"];
                header("Location: room.php");
                exit;
            }else{$errors[] = "Invalid email or password.";}            
        } catch (PDOException $e) {
            $errors[] = "Login could not be completed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1 style="text-align: center;">Welcome Back</h1>

    <form method="POST" action="login.php" novalidate>
        <?php foreach ($errors as $error): ?>
            <p class="error">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endforeach; ?>

        <label for="email">Email</label>
        <input
            type="email"
            name="email"
            id="email"
            value="<?= htmlspecialchars($email) ?>"
            required
        >

        <label for="password">Password</label>
        <input
            type="password"
            name="password"
            id="password"
            required
        >

        <button type="submit">Log In</button>

        <p>Don't have an account?
            <a href="register.php">Register here</a>
        </p>
    </form>
</body>
</html>
