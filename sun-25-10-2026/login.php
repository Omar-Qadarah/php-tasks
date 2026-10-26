<?php
session_start();
require_once "db.php";
require_once "user.php";

$error = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if(!empty($email) && !empty($password)){
        $db = (new Database())->connect();
        $userModel = new User($db); 

        $user = $userModel->login($email, $password);

        if ($user){
            $_SESSION["user_id"] = $user["id"]; 
            header("location: dashboard.php");  
            exit;
        } else {
            $error = "not correct";
        }
    } else {
        $error = "please write ur email and password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="auth-page">

    <form action="login.php" method="POST" class="auth-card">

        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>



        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            value="<?=htmlspecialchars($email)?>"
            required
        >

        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            required
        >

        <button type="submit" class="btn-block">Login</button>

        <p class="auth-footer">No account yet? <a href="register.php">Register</a></p>

    </form>

</body>
</html>