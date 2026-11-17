<?php
require_once __DIR__ . "/../model/connection.php";
require_once __DIR__ . "/../model/Lecturer.php";
require_once __DIR__ . "/../model/Courses.php";
require_once __DIR__ . "/../model/Schedules.php";

$entity = $_GET["entity"] ?? "";
if (!is_string($entity) || !in_array($entity, ["lecturers", "courses", "schedules"], true)) {
    header("Location: index.php");
    exit;
}

$errors = [
    "load" => "Unable to load " . $entity . ".",
    "delete" => "Unable to delete the selected record.",
    "not_found" => "The requested record was not found.",
    "invalid_id" => "Invalid record identifier.",
];
$notices = [
    "saved" => "Record saved.",
    "deleted" => "Record deleted.",
];
$errorCode = $_GET["error"] ?? "";
$noticeCode = $_GET["notice"] ?? "";
$error = is_string($errorCode) ? ($errors[$errorCode] ?? "") : "";
$notice = is_string($noticeCode) ? ($notices[$noticeCode] ?? "") : "";
$lecturers = [];
$courses = [];
$schedules = [];

try {
    switch ($entity) {
        case "lecturers":
            $lecturers = (new Lecturer($conn))->all();
            $view = "lecturers.php";
            break;
        case "courses":
            $courses = (new Courses($conn))->all();
            $view = "courses.php";
            break;
        case "schedules":
            $schedules = (new Schedules($conn))->all();
            $view = "schedules.php";
            break;
    }
} catch (Throwable $exception) {
    $error = $errors["load"];
    $view = $entity . ".php";
}

define("MVC_REQUEST", true);
require __DIR__ . "/../view/" . $view;
