<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require __DIR__ ."/config/db.php";
$users = $pdo->query("SELECT id, name, email FROM users ORDER BY id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1 class="welcome">Welcome <?=htmlspecialchars($_SESSION["user_name"])?></h1>
    <a href="logout.php" class="log_out">Log out</a>

    <table class="users">
        <thead>
            <tr><th>ID</th><th>Name</th><th>Email</th></tr>
        </thead>
        <tbody class="user-row">
            <?php foreach ($users as $user): ?>
                <tr class="users">
                    <td><?=htmlspecialchars($user['id'])?></td>
                    <td><?=htmlspecialchars($user['name'])?></td>
                    <td><?=htmlspecialchars($user['email'])?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>