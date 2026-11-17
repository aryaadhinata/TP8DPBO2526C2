<?php
require_once __DIR__ . "/../model/connection.php";
require_once __DIR__ . "/../model/Schedules.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: schedules.php");
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header("Location: schedules.php?error=invalid_id");
    exit;
}

try {
    (new Schedules($conn))->delete($id);
    header("Location: schedules.php?notice=deleted");
} catch (Throwable $exception) {
    header("Location: schedules.php?error=delete");
}
exit;
