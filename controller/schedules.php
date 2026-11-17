<?php
require_once __DIR__ . "/../model/connection.php";
require_once __DIR__ . "/../model/Schedules.php";

$schedules = [];
$error = "";
$notice = "";
$errors = [
    "load" => "Unable to load schedules.",
    "delete" => "Unable to delete schedule.",
    "not_found" => "The requested schedule was not found.",
    "invalid_id" => "Invalid schedule identifier.",
];
$notices = [
    "saved" => "Schedule saved.",
    "deleted" => "Schedule deleted.",
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
    $schedules = (new Schedules($conn))->all();
} catch (Throwable $exception) {
    $error = $errors["load"];
}

define("MVC_REQUEST", true);
require __DIR__ . "/../view/schedules.php";
