<?php
session_start();
require_once __DIR__ ."/config/Database.php";
require_once __DIR__ ."/classes/student.php";
$database = new Database();
$pdo = $database->getConnection();

$student = new Student($pdo);

$errors = [];
$success = '';
$firstname = '';
$lastname = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ??'');
    $email = trim($_POST['email'] ??'');
    $password = $_POST['password'] ??'';
    $confirmPassword = $_POST['confirmPassword'] ??'';

    if ($firstname == ''){
        $errors[] = 'First name is requered.';
    }
    if ($lastname == '') {
        $errors[] = 'Last name is requered';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }
    if($password !== $confirmPassword){
        $errors[] = "Passwords don't match";
    }
    

    if (empty($errors)) {
            if($student->emailExists($email)){
        $errors[] = "This email already registered.";
        }else{
            try{
                $student->register($firstname, $lastname, $email, $password);
                $success = "Registered successfuly";
                $firstname = "";
                $lastname = "";
                $email = "";
                $studentData = $student->findByEmail($email);               
                session_regenerate_id(true);
                $_SESSION["student_id"] = (int) $studentData["id"];
                $_SESSION["student_name"] = $studentData["first_name"] . " " . $studentData["last_name"];
                header("Location: room.php");
                exit;
            }catch(Exception $e){
                $errors[]="Registration could not be completed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1 style="text-align: center;">Create Your Account</h1>

    <form method="POST" action="register.php">
        <?php foreach($errors as $error):?>
            <p class="error"><?=htmlspecialchars($error) ?></p>        
        <?php endforeach;?>
        <?php if($success !==""):?>
            <p class="success"><?=htmlspecialchars($success)?></p>
        <?php endif;?>
        <label for="firstname">First name</label>
        <input type="text" name="firstname" id="firstname" value="<?=htmlspecialchars($firstname)?>" required>
        <label for="lastname">Last name</label>
        <input type="text" name="lastname" id="lastname" value="<?=htmlspecialchars($lastname)?>" required>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?=htmlspecialchars($email)?>" required>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" minlength="8" required>
        <label for="password">Confirm password</label>
        <input type="password" name="confirmPassword" id="confirmPassword" required>
        <button>submit</button>
    </form>
</body>
</html>
