<?php
// model/Courses.php
class Courses
{
    // Define a private property to hold the database connection
    private mysqli $connection;

    // Constructor to initialize the Courses class with a database connection
    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    // Method to retrieve all courses along with their associated lecturer names and schedule counts
    public function all(): array
    {
        $result = $this->connection->query( // Execute a SQL query to fetch course details, lecturer names, and schedule counts
            "SELECT c.id, c.code, c.name, c.credits, c.lecturer_id, l.name AS lecturer_name,
                    COUNT(s.id) AS schedule_count
            FROM courses c
            LEFT JOIN lecturers l ON l.id = c.lecturer_id
            LEFT JOIN schedules s ON s.course_id = c.id
            GROUP BY c.id, c.code, c.name, c.credits, c.lecturer_id, l.name
            ORDER BY c.code"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Method to find a course by its ID and return its details as an associative array
    public function findById(int $id): ?array
    {
        // Prepare a SQL statement to select the course details based on the provided ID
        $statement = $this->connection->prepare(
            "SELECT id, code, name, credits, lecturer_id FROM courses WHERE id = ?"
        );
        $statement->bind_param("i", $id);
        $statement->execute();
        $statement->bind_result($foundId, $code, $name, $credits, $lecturerId);

        // Fetch the result and return it as an associative array, or return null if not found
        if (!$statement->fetch()) {
            $statement->close();
            return null;
        }

        // Close the statement and return the course details as an associative array
        $statement->close();
        return [
            "id" => $foundId,
            "code" => $code,
            "name" => $name,
            "credits" => $credits,
            "lecturer_id" => $lecturerId,
        ];
    }

    // Method to create a new course record in the database
    public function create(string $code, string $name, int $credits, ?int $lecturerId): void
    {
        $statement = $this->connection->prepare(
            "INSERT INTO courses (code, name, credits, lecturer_id) VALUES (?, ?, ?, ?)"
        );
        $statement->bind_param("ssii", $code, $name, $credits, $lecturerId);
        $statement->execute();
        $statement->close();
    }

    // Method to update an existing course record in the database
    public function update(
        int $id,
        string $code,
        string $name,
        int $credits,
        ?int $lecturerId
    ): void { // Prepare a SQL statement to update the course details based on the provided ID
        $statement = $this->connection->prepare(
            "UPDATE courses SET code = ?, name = ?, credits = ?, lecturer_id = ? WHERE id = ?"
        );
        $statement->bind_param("ssiii", $code, $name, $credits, $lecturerId, $id);
        $statement->execute();
        $statement->close();
    }

    // Method to delete a course record from the database based on its ID
    public function delete(int $id): void
    {
        $statement = $this->connection->prepare("DELETE FROM courses WHERE id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();
        $statement->close();
    }
}