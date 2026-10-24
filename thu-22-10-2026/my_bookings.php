
<?php
session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/config/Database.php";
require_once __DIR__ . "/classes/Booking.php";


$database = new Database();
$pdo = $database->getConnection();

$booking = new Booking($pdo);


$studentId = (int) $_SESSION["student_id"];
$bookings = $booking->getByStudent($studentId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" href="style.css">
</head>
    <body>

        <h1>My Bookings</h1>

        <p>
            Welcome, <?= htmlspecialchars($_SESSION["student_name"]) ?>!
        </p>

        <p>
            <a href="room.php">Browse Rooms</a> |
            <a href="book_room.php">Book a Room</a> |
            <a href="logout.php">Logout</a>
        </p>

        <?php if (empty($bookings)): ?>

            <p>You don't have any bookings yet.</p>
            <a href="room.php">Find a room</a>

        <?php else: ?>

            <table border="1" cellpadding="10">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Room</th>
                        <th>Capacity</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($bookings as $bookingItem): ?>
                        <tr>
                            <td>
                                <?= (int) $bookingItem["id"] ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($bookingItem["room_title"]) ?>
                            </td>

                            <td>
                                <?= (int) $bookingItem["capacity"] ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($bookingItem["start_time"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($bookingItem["end_time"]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>

    </body>
</html>

