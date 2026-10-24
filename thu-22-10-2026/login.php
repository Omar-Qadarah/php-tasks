
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
        <title>Login | StudySpace</title>
        <link rel="stylesheet" href="assets/style.css">
    </head>
    <body class="auth-page">
        <main class="auth-container">
            <section class="auth-card">

                <div class="auth-brand">
                    <h1>Welcome Back</h1>
                    <p>Sign in to book your study space.</p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="auth-message auth-error">
                        <?php foreach ($errors as $error): ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="POST" class="auth-form">

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" autocomplete="email" value="<?=htmlspecialchars($email)?>" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" autocomplete="current-password" required>
                    </div>

                    <button type="submit" class="btn">Login</button>

                </form>

                <p class="auth-footer">Don't have an account? <a href="register.php">Create one</a></p>
            </section>
        </main>                
    </body>
</html>

