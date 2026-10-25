<?php

session_start();
require_once"db.php";
require_once"user.php";

if (!isset($_SESSION["user_id"])) {
    echo "No user session found.";
    exit;
}
$db=(new Database())->connect();
$userModel = new user($db);
$result=$userModel -> getAllUsers();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to dash</h1>
    <a href="logout.php" class="btn">logout</a>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Delete</th>
                <th>Update</th>
            </tr>
        </thead>
    
        <tbody id="usersBody">
            
        </tbody>
    </table>
    
</body>
</html>