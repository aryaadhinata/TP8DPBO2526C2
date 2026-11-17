<?php
require_once __DIR__ . "/../model/connection.php";
require_once __DIR__ . "/../model/Lecturer.php";

$lecturers = [];
$error = "";

try {
    $lecturers = (new Lecturer($conn))->all();
} catch (Throwable $exception) {
    $error = "Unable to load lecturers.";
}

define("MVC_REQUEST", true);
require __DIR__ . "/../view/lecturers.php";
