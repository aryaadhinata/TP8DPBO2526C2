<?php
require_once __DIR__ . "/../model/connection.php";
require_once __DIR__ . "/../model/Courses.php";

$courses = [];
$error = "";
$notice = "";
$errors = [
    "load" => "Unable to load courses.",
    "delete" => "Unable to delete course.",
    "not_found" => "The requested course was not found.",
    "invalid_id" => "Invalid course identifier.",
];
$notices = [
    "saved" => "Course saved.",
    "deleted" => "Course deleted.",
];
$errorCode = $_GET["error"] ?? "";
$noticeCode = $_GET["notice"] ?? "";
if (is_string($errorCode) && isset($errors[$errorCode])) {
    $error = $errors[$errorCode];
}
if (is_string($noticeCode) && isset($notices[$noticeCode])) {
    $notice = $notices[$noticeCode];
}

try {
    $courses = (new Courses($conn))->all();
} catch (Throwable $exception) {
    $error = $errors["load"];
}

define("MVC_REQUEST", true);
require __DIR__ . "/../view/courses.php";
