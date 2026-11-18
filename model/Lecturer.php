<?php
class Lecturer
{ // Represents a lecturer and provides methods to interact with the lecturers table in the database
    private mysqli $connection;

    // Constructor to initialize the Lecturer class with a database connection
    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    // Method to retrieve all lecturers along with their associated course counts and course codes
    public function all(): array
    {
        // Execute a SQL query to fetch lecturer details, course counts, and course codes
        $result = $this->connection->query(
            "SELECT l.id, l.name, l.nidn, l.phone, l.join_date,
                    COUNT(c.id) AS course_count,
                    GROUP_CONCAT(c.code ORDER BY c.code SEPARATOR ', ') AS course_codes
            FROM lecturers l
            LEFT JOIN courses c ON c.lecturer_id = l.id
            GROUP BY l.id, l.name, l.nidn, l.phone, l.join_date
            ORDER BY l.id"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Method to find a lecturer by their ID and return their details as an associative array
    public function findById(int $id): ?array
    {
        $statement = $this->connection->prepare(
            "SELECT id, name, nidn, phone, join_date FROM lecturers WHERE id = ?"
        );
        $statement->bind_param("i", $id);
        $statement->execute();
        $statement->bind_result($foundId, $name, $nidn, $phone, $joinDate);

        // Fetch the result and return it as an associative array, or return null if not found
        if (!$statement->fetch()) {
            $statement->close();
            return null;
        }

        // Close the statement and return the lecturer details as an associative array
        $statement->close();
        return [
            "id" => $foundId,
            "name" => $name,
            "nidn" => $nidn,
            "phone" => $phone,
            "join_date" => $joinDate,
        ];
    }

    // Method to create a new lecturer record in the database
    public function create(string $name, string $nidn, string $phone, string $joinDate): void
    {
        $statement = $this->connection->prepare(
            "INSERT INTO lecturers (name, nidn, phone, join_date) VALUES (?, ?, ?, ?)"
        );
        $statement->bind_param("ssss", $name, $nidn, $phone, $joinDate);
        $statement->execute();
        $statement->close();
    }

    // Method to update an existing lecturer record in the database
    public function update(int $id, string $name, string $nidn, string $phone, string $joinDate): void
    {
        $statement = $this->connection->prepare(
            "UPDATE lecturers SET name = ?, nidn = ?, phone = ?, join_date = ? WHERE id = ?"
        );
        $statement->bind_param("ssssi", $name, $nidn, $phone, $joinDate, $id);
        $statement->execute();
        $statement->close();
    }

    // Method to delete a lecturer record from the database based on their ID
    public function delete(int $id): void
    {
        $statement = $this->connection->prepare("DELETE FROM lecturers WHERE id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();
        $statement->close();
    }
}
