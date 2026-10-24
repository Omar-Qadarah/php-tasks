<?php
session_start();

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/config/Database.php";
require_once __DIR__ . "/classes/Room.php";
require_once __DIR__ . "/classes/Booking.php";

$database = new Database();
$pdo = $database->getConnection();

$room = new Room($pdo);
$booking = new Booking($pdo);

$rooms = $room->getAll();

$errors = [];
$success = "";
$roomId = $_GET["room_id"] ?? "";
$startTime = "";
$endTime = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $roomId = trim($_POST["room_id"] ?? "");
    $startTime = trim($_POST["start_time"] ?? "");
    $endTime = trim($_POST["end_time"] ?? "");

    $validatedRoomId = filter_var($roomId, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);

    if ($validatedRoomId === false) {
        $errors[] = "Please select a valid room.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM rooms WHERE id = ?" );
        $stmt->execute([$validatedRoomId]);
        if ($stmt->fetch() === false) {
            $errors[] = "The selected room does not exist.";
        }
    }

    $startDate = DateTime::createFromFormat( '!Y-m-d\TH:i', $startTime);

    $endDate = DateTime::createFromFormat('!Y-m-d\TH:i',$endTime);

    if (!$startDate || $startDate->format('Y-m-d\TH:i') !== $startTime) {
        $errors[] = "Please enter a valid start time.";
    }

    if ( !$endDate || $endDate->format('Y-m-d\TH:i') !== $endTime) {
        $errors[] = "Please enter a valid end time.";
    }

    if ($startDate && $endDate) {
        $now = new DateTime();
        if ($startDate <= $now) {
            $errors[] = "The start time must be in the future.";
        }
        if ($endDate <= $startDate) {
            $errors[] = "The end time must be after the start time.";
        }
    }

    if (empty($errors)) {
        try {
            $startForDatabase = $startDate->format('Y-m-d H:i:s');
            $endForDatabase = $endDate->format('Y-m-d H:i:s');

            if (
                $booking->hasConflict(
                    $validatedRoomId,
                    $startForDatabase,
                    $endForDatabase
                )
            ) {
                $errors[] = "This room is already booked during that time.";
            } else {
                $booking->createBooking(
                    (int) $_SESSION["student_id"],
                    $validatedRoomId,
                    $startForDatabase,
                    $endForDatabase
                );

                $success = "Room booked successfully!";

                $roomId = "";
                $startTime = "";
                $endTime = "";
            }
        } catch (PDOException $e) {
            $errors[] = "The booking could not be completed. Please try again.";
        }
    }


}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Study Room</title>
    <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Book a Study Room</h1>
    
        <?php foreach ($errors as $error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>
        
        <?php if ($success !== ""): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        
        <form method="POST" action="book_room.php">
            <label for="room_id">Choose a room</label>
        
            <select name="room_id" id="room_id" required>
                <option value="">-- Select a room --</option>
        
                <?php foreach ($rooms as $roomItem): ?>
                    <option
                        value="<?= (int) $roomItem['id'] ?>"
                        <?= (string) $roomId === (string) $roomItem['id'] ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($roomItem['title']) ?> (Capacity: <?= (int) $roomItem['capacity'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
                
            <label for="start_time">Start time</label>
            <input
                type="datetime-local"
                name="start_time"
                id="start_time"
                value="<?= htmlspecialchars($startTime) ?>"
                required
            >
                
            <label for="end_time">End time</label>
            <input
                type="datetime-local"
                name="end_time"
                id="end_time"
                value="<?= htmlspecialchars($endTime) ?>"
                required
            >
                
            <button type="submit">Book Room</button>
        </form>
                
        <p><a href="room.php">Back to rooms</a></p>
    </body>
</html>
