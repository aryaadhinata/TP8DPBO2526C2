<?php
require_once __DIR__ . "/../model/connection.php";
require_once __DIR__ . "/../model/Lecturer.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if ($id) {
    (new Lecturer($conn))->delete($id);
}

header("Location: lecturers.php");
exit;
