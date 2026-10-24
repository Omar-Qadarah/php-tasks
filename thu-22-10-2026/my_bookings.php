
<?php
session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}
$message = $_SESSION["booking_message"] ?? "";
unset($_SESSION["booking_message"]);

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
    <link rel="stylesheet" href="assets/style.css">
</head>
    <body>

        <h1>My Bookings</h1>
        <?php if ($message !== ""): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <p>
            Welcome, <?= htmlspecialchars($_SESSION["student_name"]) ?>!
        </p>
        <p>
            <a href="room.php" class="nav">Browse Rooms</a> |
            <a href="book_room.php" class="nav">Book a Room</a> |
            <a href="logout.php" class="nav">Logout</a>
        </p>

        <?php if (empty($bookings)): ?>

            <p>You don't have any bookings yet.</p>
            <a href="room.php">Find a room</a>

        <?php else: ?>
            <div class="table-wrapper">
                <table class="booking-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Room</th>
                            <th>Capacity</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Action</th>
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
                                <td>
                                    <form action="cancel_booking.php" method="POST"
                                          onsubmit="return confirm('Are you sure you want to cancel this booking?');">

                                        <input
                                            type="hidden"
                                            name="booking_id"
                                            value="<?= (int) $bookingItem['id'] ?>"
                                        >

                                        <button type="submit">Cancel Booking</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </body>
</html>

