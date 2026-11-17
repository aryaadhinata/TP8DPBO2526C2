<?php
require_once __DIR__ . "/../model/connection.php";
require_once __DIR__ . "/../model/Courses.php";

$courses = [];
$error = "";

try {
    $courses = (new Courses($conn))->all();
} catch (Throwable $exception) {
    $error = "Unable to load courses.";
}

define("MVC_REQUEST", true);
require __DIR__ . "/../view/courses.php";
