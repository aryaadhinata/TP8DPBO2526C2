<?php
require_once __DIR__ . "/../model/connection.php";
require_once __DIR__ . "/../model/Courses.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: courses.php");
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header("Location: courses.php?error=invalid_id");
    exit;
}

try {
    (new Courses($conn))->delete($id);
    header("Location: courses.php?notice=deleted");
} catch (Throwable $exception) {
    header("Location: courses.php?error=delete");
}
exit;
