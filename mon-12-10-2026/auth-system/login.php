<?php
session_start();
require __DIR__ . "/config/db.php";

$error = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $error = "Enter your email and password please.";
    }

    if ($user && password_verify($password, $user["password"])) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];
        header("Location: users.php");
        exit;
    } else {
            $error = "Invalid email or password.";
    }
    
    if ($error !== "") {
        $stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <form action="" method="post" novalidate>
        <?php if($error!== ""): ?>
            <span class="error"><?=htmlspecialchars($error)?></span>
        <?php endif; ?>
        <label for="email">Email</label>
        <input type="text" id="email" name="email" value="<?= htmlspecialchars($email) ?>">
        <label for="password">password</label>
        <input type="password" id="password" name="password">        
        <button type="submit" class="submit">Login</button>
    </form>
    
</body>
</html>