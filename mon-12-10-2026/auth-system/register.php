<?php
require __DIR__ . "/config/db.php";
$errors=[];
$name = "";
$email = "";
$password = "";
$confirmPassword="";
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ??"");
    $password = $_POST["password"] ??"";
    $confirmPassword = $_POST["confirm-password"] ??"";
    if($name == ""){
        $errors['name'] = "Enter your name please.";
    }
    if($email == ""){
        $errors["email"] = "Enter your email please.";
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors["email"] = 'Invalid email format e.g. omar@example.com';
    }else{
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors["email"] = "This email is already registered.";
        }
    }
    if($password == ""){
        $errors["password"] = 'Enter your password please.';
    }elseif(strlen($password) < 8){
        $errors['password'] = 'Password minimum length is 8';
    }
    if($password !== $confirmPassword){
        $errors['confirm'] = "Passwords don't match";
    }
    

    if(empty($errors)){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hash]);
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="" method="post" novalidate class="Register">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>">
        <?php if(isset($errors['name'])): ?>
            <span class="error"><?=htmlspecialchars($errors['name'])?></span>
        <?php endif;?>   
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>">
        <?php if(isset($errors['email'])): ?>
            <span class="error"><?=htmlspecialchars($errors['email'])?></span>
        <?php endif;?>           
        <label for="password">Password</label>
        <input type="password" name="password" id="password" minlength="8">
                <?php if(isset($errors['password'])): ?>
            <span class="error"><?=htmlspecialchars($errors['password'])?></span>
        <?php endif;?>   
        <label for="confirm-password">Confirm password</label>
        <input type="password" name="confirm-password" id="confirm-password">
        <?php if(isset($errors['confirm'])): ?>
            <span class="error"><?=htmlspecialchars($errors['confirm'])?></span>
        <?php endif;?>

        <button class="submit">Register</button>
        <a href="login.php" class="btn">Log in</a>
    </form>
</body>
</html>