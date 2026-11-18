<?php
class Courses
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function all(): array
    {
        $result = $this->connection->query(
            "SELECT c.id, c.code, c.name, c.credits, c.lecturer_id, l.name AS lecturer_name
            FROM courses c
            LEFT JOIN lecturers l ON l.id = c.lecturer_id
            ORDER BY c.code"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $statement = $this->connection->prepare(
            "SELECT id, code, name, credits, lecturer_id FROM courses WHERE id = ?"
        );
        $statement->bind_param("i", $id);
        $statement->execute();
        $statement->bind_result($foundId, $code, $name, $credits, $lecturerId);

        if (!$statement->fetch()) {
            $statement->close();
            return null;
        }

        $statement->close();
        return [
            "id" => $foundId,
            "code" => $code,
            "name" => $name,
            "credits" => $credits,
            "lecturer_id" => $lecturerId,
        ];
    }

    public function create(string $code, string $name, int $credits, ?int $lecturerId): void
    {
        $statement = $this->connection->prepare(
            "INSERT INTO courses (code, name, credits, lecturer_id) VALUES (?, ?, ?, ?)"
        );
        $statement->bind_param("ssii", $code, $name, $credits, $lecturerId);
        $statement->execute();
        $statement->close();
    }

    public function update(
        int $id,
        string $code,
        string $name,
        int $credits,
        ?int $lecturerId
    ): void {
        $statement = $this->connection->prepare(
            "UPDATE courses SET code = ?, name = ?, credits = ?, lecturer_id = ? WHERE id = ?"
        );
        $statement->bind_param("ssiii", $code, $name, $credits, $lecturerId, $id);
        $statement->execute();
        $statement->close();
    }

    public function delete(int $id): void
    {
        $statement = $this->connection->prepare("DELETE FROM courses WHERE id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();
        $statement->close();
    }
}