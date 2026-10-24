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
                $studentData = $student->findByEmail($email);               
                $success = "Registered successfuly";
                $firstname = "";
                $lastname = "";
                $email = "";
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
    <title>Register | StudySpace</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="auth-page">

    <main class="auth-container">
        <section class="auth-card">

            <div class="auth-brand">
                <h1>Create Account</h1>
                <p>Join StudySpace and reserve your room.</p>
            </div>
            <?php if (!empty($errors)): ?>
                <div class="auth-message auth-error">
                    <?php foreach($errors as $error):?>
                        <p class="error"><?=htmlspecialchars($error) ?></p>        
                    <?php endforeach;?>
                    <?php if($success !==""):?>
                        <p class="success"><?=htmlspecialchars($success)?></p>
                    <?php endif;?>
                </div>
            <?php endif; ?>           

            <form action="register.php" method="POST" class="auth-form">

                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" autocomplete="given-name" value="<?=htmlspecialchars($firstname)?>" required>
                </div>

                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" autocomplete="family-name" value="<?=htmlspecialchars($lastname)?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input  type="email" id="email" name="email" autocomplete="email" value="<?=htmlspecialchars($email)?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" autocomplete="new-password" required>
                </div>
                <div class="form-group">
                    <label for="password">Confirm password</label>
                    <input type="password" name="confirmPassword" id="confirmPassword" required>
                </div>

                <button type="submit" class="btn">
                    Create Account
                </button>

            </form>

            <p class="auth-footer"> Already have an account? <a href="login.php">Login</a></p>
        </section>
    </main>

</body>
</html>
