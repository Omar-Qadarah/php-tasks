<?php

class Booking
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function hasConflict($roomId, $startTime, $endTime)
    {
        $sql = "SELECT id
                FROM bookings
                WHERE room_id = ?
                AND start_time < ?
                AND end_time > ?
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            $roomId,
            $endTime,
            $startTime
        ]);

        return $stmt->fetch() !== false;
    }

    public function createBooking($studentId, $roomId, $startTime, $endTime)
    {
        $sql = "INSERT INTO bookings
                (student_id, room_id, start_time, end_time)
                VALUES (?, ?, ?, ?)";
    
        $stmt = $this->pdo->prepare($sql);
    
        return $stmt->execute([
        $studentId,
        $roomId,
        $startTime,
        $endTime
        ]);
    }
    
    public function getByStudent($studentId)
    {
        $sql = "SELECT
                    bookings.id,
                    bookings.start_time,
                    bookings.end_time,
                    rooms.title AS room_title,
                    rooms.capacity
                FROM bookings
                INNER JOIN rooms ON bookings.room_id = rooms.id
                WHERE bookings.student_id = ?
                ORDER BY bookings.start_time ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$studentId]);

        return $stmt->fetchAll();
    }

}