<?php
require_once __DIR__ . "/../model/connection.php";
require_once __DIR__ . "/../model/Lecturer.php";
require_once __DIR__ . "/../model/Courses.php";
require_once __DIR__ . "/../model/Schedules.php";

$entity = $_GET["entity"] ?? "";
if (
    $_SERVER["REQUEST_METHOD"] !== "POST"
    || !is_string($entity)
    || !in_array($entity, ["lecturers", "courses", "schedules"], true)
) {
    header("Location: index.php");
    exit;
}

$id = filter_var($_POST["id"] ?? null, FILTER_VALIDATE_INT);
if ($id === false || $id < 1) {
    header("Location: read.php?entity=" . rawurlencode($entity) . "&error=invalid_id");
    exit;
}

try {
    switch ($entity) {
        case "lecturers":
            (new Lecturer($conn))->delete($id);
            break;
        case "courses":
            (new Courses($conn))->delete($id);
            break;
        case "schedules":
            (new Schedules($conn))->delete($id);
            break;
    }
    header("Location: read.php?entity=" . rawurlencode($entity) . "&notice=deleted");
} catch (Throwable $exception) {
    header("Location: read.php?entity=" . rawurlencode($entity) . "&error=delete");
}
exit;
