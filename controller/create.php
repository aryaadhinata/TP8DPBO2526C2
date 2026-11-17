<?php
require_once __DIR__ . "/../model/connection.php";
require_once __DIR__ . "/../model/Lecturer.php";
require_once __DIR__ . "/../model/Courses.php";
require_once __DIR__ . "/../model/Schedules.php";

function createPostValue(string $key): string
{
    $value = $_POST[$key] ?? "";
    return is_string($value) ? trim($value) : "";
}

$entity = $_GET["entity"] ?? "";
if (!is_string($entity) || !in_array($entity, ["lecturers", "courses", "schedules"], true)) {
    header("Location: index.php");
    exit;
}

$values = [
    "lecturers" => ["name" => "", "nidn" => "", "phone" => "", "join_date" => ""],
    "courses" => ["code" => "", "name" => "", "credits" => "2", "lecturer_id" => ""],
    "schedules" => [
        "course_id" => "",
        "day_of_week" => "",
        "start_time" => "",
        "end_time" => "",
        "room" => "",
    ],
][$entity];
$error = "";
$options = ["lecturers" => [], "courses" => []];
$days = Schedules::DAYS;

try {
    if ($entity === "courses") {
        $options["lecturers"] = (new Lecturer($conn))->all();
    } elseif ($entity === "schedules") {
        $options["courses"] = (new Courses($conn))->all();
        if ($options["courses"] === []) {
            $error = "Create a course before adding a schedule.";
        }
    }
} catch (Throwable $exception) {
    $error = $entity === "courses"
        ? "Unable to load lecturer options."
        : "Unable to load course options.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach (array_keys($values) as $field) {
        $values[$field] = createPostValue($field);
    }

    if ($entity === "lecturers") {
        if (in_array("", $values, true)) {
            $error = "All lecturer fields are required.";
        } else {
            try {
                (new Lecturer($conn))->create(
                    $values["name"],
                    $values["nidn"],
                    $values["phone"],
                    $values["join_date"]
                );
            } catch (Throwable $exception) {
                $error = "Unable to save lecturer. Check that the NIDN is unique.";
            }
        }
    } elseif ($entity === "courses") {
        $credits = filter_var(
            $values["credits"],
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1, "max_range" => 255]]
        );
        $lecturerId = null;
        if ($values["lecturer_id"] !== "") {
            $lecturerId = filter_var($values["lecturer_id"], FILTER_VALIDATE_INT);
            $lecturerIds = array_map("intval", array_column($options["lecturers"], "id"));
        }

        if ($values["code"] === "" || $values["name"] === "" || $credits === false) {
            $error = "Enter a course code, name, and a credit value from 1 to 255.";
        } elseif (
            $values["lecturer_id"] !== ""
            && ($lecturerId === false || !in_array($lecturerId, $lecturerIds, true))
        ) {
            $error = "Select a valid lecturer or leave the course unassigned.";
        } elseif ($error === "") {
            try {
                (new Courses($conn))->create(
                    $values["code"],
                    $values["name"],
                    $credits,
                    $lecturerId
                );
            } catch (Throwable $exception) {
                $error = "Unable to save course. Check that the course code is unique.";
            }
        }
    } else {
        $courseId = filter_var($values["course_id"], FILTER_VALIDATE_INT);
        $courseIds = array_map("intval", array_column($options["courses"], "id"));
        $validTimes = preg_match('/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/D', $values["start_time"])
            && preg_match('/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/D', $values["end_time"]);

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
            } catch (Throwable $exception) {
                $error = "Unable to save schedule. It may duplicate an existing schedule.";
            }
        }
    }

    if ($error === "") {
        header("Location: read.php?entity=" . rawurlencode($entity) . "&notice=saved");
        exit;
    }
}

$titles = [
    "lecturers" => "Create Lecturer",
    "courses" => "Add Course",
    "schedules" => "Add Schedule",
];
$formTitle = $titles[$entity];
$formAction = "create.php?entity=" . rawurlencode($entity);
$lecturers = $options["lecturers"];
$courses = $options["courses"];

define("MVC_REQUEST", true);
if ($entity === "lecturers") {
    require __DIR__ . "/../view/create.php";
} elseif ($entity === "courses") {
    require __DIR__ . "/../view/course_form.php";
} else {
    require __DIR__ . "/../view/schedule_form.php";
}
