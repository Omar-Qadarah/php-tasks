<?php
require_once "db.php";
require_once "user.php";

$message ="";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"];

    if (!empty($name) && !empty($email) && strlen($password) >=8 ){
        $db = (new Database())->connect();
        $userModel= new user($db);

        if ($userModel->register($name, $email, $password)){
            header("location: login.php");
            exit;
        } else{
            $message= "Faild";
        }

    }else{
        $message="fill correct";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="auth-page">

    <form action="register.php" method="POST" class="auth-card">
        <h2>Create Account</h2>
        
        <?php if (!empty($message)): ?>
            <p class="error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <label for="name">Name</label>
        <input
            type="text"
            id="name"
            name="name"
            required
        >

        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            required
        >

        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            required
            minlength="8"
        >

        <button type="submit" class="btn-block">Register</button>

        <p class="auth-footer">Already have an account? <a href="login.php">Login</a></p>
    </form>

</body>
</html>
