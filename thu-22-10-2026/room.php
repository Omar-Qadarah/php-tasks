<?php
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Our study rooms</h1>
    <div class="rooms-container">
        <?php if (empty($rooms)): ?>
            <p>No study rooms are available yet.</p>
        <?php else: ?>
            <?php foreach ($rooms as $room): ?>
                <div class="room-card">
                    <h2>
                        <?= htmlspecialchars($room['title']) ?>
                    </h2>
                    <p class="capacity">
                        Capacity: <?= (int) $room['capacity'] ?> students
                    </p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>