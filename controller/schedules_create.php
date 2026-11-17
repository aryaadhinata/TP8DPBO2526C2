<?php
require_once __DIR__ . "/../model/connection.php";
require_once __DIR__ . "/../model/Courses.php";
require_once __DIR__ . "/../model/Schedules.php";

$values = [
    "course_id" => "",
    "day_of_week" => "",
    "start_time" => "",
    "end_time" => "",
    "room" => "",
];
$courses = [];
$days = Schedules::DAYS;
$error = "";
$formTitle = "Add Schedule";
$formAction = "schedules_create.php";

try {
    $courses = (new Courses($conn))->all();
} catch (Throwable $exception) {
    $error = "Unable to load courses.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach (["course_id", "day_of_week", "start_time", "end_time", "room"] as $field) {
        $values[$field] = trim($_POST[$field] ?? "");
    }

    $courseId = filter_var($values["course_id"], FILTER_VALIDATE_INT);
    $courseIds = array_map("intval", array_column($courses, "id"));
    $timePattern = '/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/D';
    $validTimes = preg_match($timePattern, $values["start_time"])
        && preg_match($timePattern, $values["end_time"]);

    if ($error === "" && ($courseId === false || !in_array($courseId, $courseIds, true))) {
        $error = "Select a valid course.";
    } elseif ($error === "" && !in_array($values["day_of_week"], $days, true)) {
        $error = "Select a valid day.";
    } elseif (
        $error === ""
        && (
            !$validTimes
            || $values["end_time"] <= $values["start_time"]
            || $values["room"] === ""
        )
    ) {
        $error = "Enter a room and valid times where the end is after the start.";
    }

    if ($error === "") {
        try {
            (new Schedules($conn))->create(
                $courseId,
                $values["day_of_week"],
                $values["start_time"],
                $values["end_time"],
                $values["room"]
            );
            header("Location: schedules.php?notice=saved");
            exit;
        } catch (Throwable $exception) {
            $error = "Unable to save schedule. It may duplicate an existing schedule.";
        }
    }
}

define("MVC_REQUEST", true);
require __DIR__ . "/../view/schedule_form.php";
