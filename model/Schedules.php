<?php
class Schedules
{
    public const DAYS = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];

    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function all(): array
    {
        $result = $this->connection->query(
            "SELECT s.id, s.course_id, c.code AS course_code, c.name AS course_name,
                    s.day_of_week, s.start_time, s.end_time, s.room
             FROM schedules s
             INNER JOIN courses c ON c.id = s.course_id
             ORDER BY s.day_of_week, s.start_time, c.code"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $statement = $this->connection->prepare(
            "SELECT id, course_id, day_of_week, start_time, end_time, room
             FROM schedules
             WHERE id = ?"
        );
        $statement->bind_param("i", $id);
        $statement->execute();
        $statement->bind_result($foundId, $courseId, $dayOfWeek, $startTime, $endTime, $room);

        if (!$statement->fetch()) {
            $statement->close();
            return null;
        }

        $statement->close();
        return [
            "id" => $foundId,
            "course_id" => $courseId,
            "day_of_week" => $dayOfWeek,
            "start_time" => $startTime,
            "end_time" => $endTime,
            "room" => $room,
        ];
    }

    public function create(
        int $courseId,
        string $dayOfWeek,
        string $startTime,
        string $endTime,
        string $room
    ): void {
        $statement = $this->connection->prepare(
            "INSERT INTO schedules (course_id, day_of_week, start_time, end_time, room)
             VALUES (?, ?, ?, ?, ?)"
        );
        $statement->bind_param("issss", $courseId, $dayOfWeek, $startTime, $endTime, $room);
        $statement->execute();
        $statement->close();
    }

    public function update(
        int $id,
        int $courseId,
        string $dayOfWeek,
        string $startTime,
        string $endTime,
        string $room
    ): void {
        $statement = $this->connection->prepare(
            "UPDATE schedules
             SET course_id = ?, day_of_week = ?, start_time = ?, end_time = ?, room = ?
             WHERE id = ?"
        );
        $statement->bind_param(
            "issssi",
            $courseId,
            $dayOfWeek,
            $startTime,
            $endTime,
            $room,
            $id
        );
        $statement->execute();
        $statement->close();
    }

    public function delete(int $id): void
    {
        $statement = $this->connection->prepare("DELETE FROM schedules WHERE id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();
        $statement->close();
    }
}