
<?php
session_start();


if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: my_bookings.php");
    exit;
}

$bookingId = filter_input(INPUT_POST,"booking_id",FILTER_VALIDATE_INT,["options" => ["min_range" => 1]]);

if ($bookingId === false || $bookingId === null) {
    header("Location: my_bookings.php");
    exit;
}

require_once __DIR__ . "/config/Database.php";
require_once __DIR__ . "/classes/Booking.php";

$database = new Database();
$pdo = $database->getConnection();

$booking = new Booking($pdo);

$studentId = (int) $_SESSION["student_id"];

try {
    $deletedRows = $booking->cancelBooking( $bookingId, $studentId);

    if ($deletedRows === 1) {
        $_SESSION["booking_message"] = "Booking cancelled successfully.";
    } else {
        $_SESSION["booking_message"] = "Booking not found or you are not authorized to cancel it.";
    }
} catch (PDOException $e) {
    $_SESSION["booking_message"] = "The booking could not be cancelled. Please try again.";
}

header("Location: my_bookings.php");
exit;
