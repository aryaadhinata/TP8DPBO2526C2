<?php
class Schedules
{ // Represents the Schedules model for managing schedule records in the database
    // Define a constant array representing the days of the week in Indonesian
    public const DAYS = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];

    // Define a private property to hold the database connection
    private mysqli $connection;

    // Constructor to initialize the Schedules class with a database connection
    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    // Method to retrieve all schedules along with their associated course codes, course names, and lecturer names
    public function all(): array
    {
        // Execute a SQL query to fetch schedule details, course codes, course names, and lecturer names
        $result = $this->connection->query(
            "SELECT s.id, s.course_id, c.code AS course_code, c.name AS course_name,
                    l.name AS lecturer_name,
                    s.day_of_week, s.start_time, s.end_time, s.room
            FROM schedules s
            INNER JOIN courses c ON c.id = s.course_id
            LEFT JOIN lecturers l ON l.id = c.lecturer_id
            ORDER BY s.day_of_week, s.start_time, c.code"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Method to find a schedule by its ID and return its details as an associative array
    public function findById(int $id): ?array
    {
        // Prepare a SQL statement to select the schedule details based on the provided ID
        $statement = $this->connection->prepare(
            "SELECT s.id, s.course_id, c.code AS course_code, c.name AS course_name,
                    l.name AS lecturer_name,
                    s.day_of_week, s.start_time, s.end_time, s.room
            FROM schedules s
            INNER JOIN courses c ON c.id = s.course_id
            LEFT JOIN lecturers l ON l.id = c.lecturer_id
            WHERE s.id = ?
            ORDER BY s.day_of_week, s.start_time, c.code"
        );
        // Bind the ID parameter to the prepared statement and execute it
        $statement->bind_param("i", $id);
        $statement->execute();
        $statement->bind_result($foundId, $foundCourseId, $foundCourseCode, $foundCourseName, $foundLecturerName, $foundDayOfWeek, $foundStartTime, $foundEndTime, $foundRoom);

        // Fetch the result and return it as an associative array, or return null if not found
        if (!$statement->fetch()) {
            $statement->close();
            return null;
        }

        // Close the statement and return the schedule details as an associative array
        $statement->close();
        return [
            "id" => $foundId,
            "course_id" => $foundCourseId,
            "course_code" => $foundCourseCode,
            "course_name" => $foundCourseName,
            "lecturer_name" => $foundLecturerName,
            "day_of_week" => $foundDayOfWeek,
            "start_time" => $foundStartTime,
            "end_time" => $foundEndTime,
            "room" => $foundRoom
        ];
    }

    // Method to find scheduling conflicts based on course ID and time slot
    public function findConflict(
        int $courseId,
        string $dayOfWeek,
        string $startTime,
        string $endTime,
        string $room,
        int $ignoreId = 0
    ): ?string { // Convert start and end times to a standardized format for comparison
        $startTime = self::toTime($startTime);
        $endTime = self::toTime($endTime);

        // Prepare a SQL statement to check for room conflicts with other schedules
        $statement = $this->connection->prepare(
            "SELECT c.code, s.start_time, s.end_time
            FROM schedules s
            INNER JOIN courses c ON c.id = s.course_id
            WHERE s.id <> ? AND s.day_of_week = ? AND s.room = ?
            AND s.start_time < ? AND s.end_time > ?
            LIMIT 1"
        );

        // Bind the parameters to the prepared statement and execute it
        $statement->bind_param("issss", $ignoreId, $dayOfWeek, $room, $endTime, $startTime);
        $statement->execute();
        $statement->bind_result($code, $foundStart, $foundEnd);
        if ($statement->fetch()) {
            $statement->close();
            return sprintf(
                "Room %s is already used by %s on %s %s-%s.",
                $room,
                $code,
                $dayOfWeek,
                substr((string) $foundStart, 0, 5),
                substr((string) $foundEnd, 0, 5)
            );
        }
        $statement->close();

        // Prepare a SQL statement to check for lecturer conflicts with other schedules
        $statement = $this->connection->prepare(
            "SELECT c.code, s.start_time, s.end_time
            FROM schedules s
            INNER JOIN courses c ON c.id = s.course_id
            WHERE s.id <> ? AND s.day_of_week = ?
            AND s.start_time < ? AND s.end_time > ?
            AND (
                s.course_id = ?
                OR c.lecturer_id = (SELECT lecturer_id FROM courses WHERE id = ?)
            )
            LIMIT 1"
        );
        // Bind the parameters to the prepared statement and execute it
        $statement->bind_param("isssii", $ignoreId, $dayOfWeek, $endTime, $startTime, $courseId, $courseId);
        $statement->execute();
        $statement->bind_result($code, $foundStart, $foundEnd);
        if ($statement->fetch()) {
            $statement->close();
            return sprintf(
                "This course or its lecturer already has %s scheduled on %s %s-%s.",
                $code,
                $dayOfWeek,
                substr((string) $foundStart, 0, 5),
                substr((string) $foundEnd, 0, 5)
            );
        }
        $statement->close();

        return null;
    }

    // Method to check if a lecturer has a scheduling clash with another course
    public function lecturerClash(int $courseId, int $lecturerId): ?string
    {
        // Prepare a SQL statement to check for scheduling clashes for the lecturer with other courses
        $statement = $this->connection->prepare(
            "SELECT cb.code, b.day_of_week, b.start_time, b.end_time
            FROM schedules a
            INNER JOIN schedules b
                    ON b.course_id <> a.course_id
                    AND b.day_of_week = a.day_of_week
                    AND b.start_time < a.end_time
                    AND b.end_time > a.start_time
            INNER JOIN courses cb ON cb.id = b.course_id
            WHERE a.course_id = ? AND cb.lecturer_id = ?
            LIMIT 1"
        );
        // Bind the parameters to the prepared statement and execute it
        $statement->bind_param("ii", $courseId, $lecturerId);
        $statement->execute();
        $statement->bind_result($code, $day, $foundStart, $foundEnd);
        if ($statement->fetch()) { // If a clash is found, return a formatted message indicating the conflict
            $statement->close();
            return sprintf(
                "This lecturer already teaches %s on %s %s-%s, which overlaps with this course's schedule.",
                $code,
                $day,
                substr((string) $foundStart, 0, 5),
                substr((string) $foundEnd, 0, 5)
            );
        }
        $statement->close();

        return null;
    }

    // Helper method to convert a time string to a standardized format (HH:MM:SS)
    private static function toTime(string $time): string
    {
        return strlen($time) === 5 ? $time . ":00" : $time;
    }

    // Method to create a new schedule record in the database
    public function create(
        int $courseId,
        string $dayOfWeek,
        string $startTime,
        string $endTime,
        string $room
    ): void { // Prepare a SQL statement to insert a new schedule record into the database
        $statement = $this->connection->prepare(
            "INSERT INTO schedules (course_id, day_of_week, start_time, end_time, room)
            VALUES (?, ?, ?, ?, ?)"
        );
        $statement->bind_param("issss", $courseId, $dayOfWeek, $startTime, $endTime, $room);
        $statement->execute();
        $statement->close();
    }

    // Method to update an existing schedule record in the database
    public function update(
        int $id,
        int $courseId,
        string $dayOfWeek,
        string $startTime,
        string $endTime,
        string $room
    ): void { // Prepare a SQL statement to update the schedule record based on the provided ID
        $statement = $this->connection->prepare(
            "UPDATE schedules
            SET course_id = ?, day_of_week = ?, start_time = ?, end_time = ?, room = ?
            WHERE id = ?"
        );
        // Bind the parameters to the prepared statement and execute it
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

    // Method to delete a schedule record from the database based on its ID
    public function delete(int $id): void
    {
        $statement = $this->connection->prepare("DELETE FROM schedules WHERE id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();
        $statement->close();
    }
}