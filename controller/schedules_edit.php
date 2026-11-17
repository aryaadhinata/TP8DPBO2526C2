<?php
require_once __DIR__ . "/../model/connection.php";
require_once __DIR__ . "/../model/Courses.php";
require_once __DIR__ . "/../model/Schedules.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header("Location: schedules.php?error=invalid_id");
    exit;
}

$model = new Schedules($conn);
try {
    $values = $model->findById($id);
} catch (Throwable $exception) {
    header("Location: schedules.php?error=load");
    exit;
}
if ($values === null) {
    header("Location: schedules.php?error=not_found");
    exit;
}

$values["start_time"] = substr($values["start_time"], 0, 5);
$values["end_time"] = substr($values["end_time"], 0, 5);
$courses = [];
$days = Schedules::DAYS;
$error = "";

try {
    $courses = (new Courses($conn))->all();
} catch (Throwable $exception) {
    $error = "Unable to load courses.";
}

$formTitle = "Edit Schedule";
$formAction = "schedules_edit.php?id=" . urlencode((string) $id);

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
            $model->update(
                $id,
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
