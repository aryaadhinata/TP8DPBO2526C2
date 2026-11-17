<?php
class Lecturer
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function all(): array
    {
        $result = $this->connection->query("SELECT * FROM lecturers");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $statement = $this->connection->prepare(
            "SELECT id, name, nidn, phone, join_date FROM lecturers WHERE id = ?"
        );
        $statement->bind_param("i", $id);
        $statement->execute();
        $statement->bind_result($foundId, $name, $nidn, $phone, $joinDate);

        if (!$statement->fetch()) {
            $statement->close();
            return null;
        }

        $statement->close();
        return [
            "id" => $foundId,
            "name" => $name,
            "nidn" => $nidn,
            "phone" => $phone,
            "join_date" => $joinDate,
        ];
    }

    public function create(string $name, string $nidn, string $phone, string $joinDate): void
    {
        $statement = $this->connection->prepare(
            "INSERT INTO lecturers (name, nidn, phone, join_date) VALUES (?, ?, ?, ?)"
        );
        $statement->bind_param("ssss", $name, $nidn, $phone, $joinDate);
        $statement->execute();
        $statement->close();
    }

    public function update(int $id, string $name, string $nidn, string $phone, string $joinDate): void
    {
        $statement = $this->connection->prepare(
            "UPDATE lecturers SET name = ?, nidn = ?, phone = ?, join_date = ? WHERE id = ?"
        );
        $statement->bind_param("ssssi", $name, $nidn, $phone, $joinDate, $id);
        $statement->execute();
        $statement->close();
    }

    public function delete(int $id): void
    {
        $statement = $this->connection->prepare("DELETE FROM lecturers WHERE id = ?");
        $statement->bind_param("i", $id);
        $statement->execute();
        $statement->close();
    }
}
