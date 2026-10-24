<?php
session_start();
if (!isset($_SESSION["student_id"])){
    header("Location: login.php");
    exit;
}
require_once __DIR__ . "/config/Database.php";
require_once __DIR__ . "/classes/room.php";
$database = new Database();
$pdo = $database->getConnection();
$roomModel = new Room($pdo);
$rooms = $roomModel->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
    <body>
        <header class="navbar">
            <strong>StudySpace</strong>
            <nav>
                <a href="room.php">Rooms</a>
                <a href="my_bookings.php">My Bookings</a>
                <a href="logout.php">Logout</a>
            </nav>
        </header>
        <main class="container">
            <h1>Find Your Study Space</h1>
            <p>Choose a room that works for you.</p>
            <div class="room-grid">
                <?php foreach ($rooms as $roomItem): ?>
                    <article class="room-card">
                        <h2>
                            <?= htmlspecialchars($roomItem["title"]) ?>
                        </h2>
                
                        <p>
                            Capacity: <?= (int) $roomItem["capacity"] ?> people
                        </p>
                
                        <a class="btn" href="book_room.php?room_id=<?= (int) $roomItem["id"] ?>">Book This Room</a>
                    </article>
                <?php endforeach; ?>
            </div>
        </main>    
    </body>
</html>