<?php
require_once __DIR__ . "/../model/connection.php";
require_once __DIR__ . "/../model/Courses.php";
require_once __DIR__ . "/../model/Lecturer.php";

$values = [
    "code" => "",
    "name" => "",
    "credits" => "2",
    "lecturer_id" => "",
];
$lecturers = [];
$error = "";
$formTitle = "Add Course";
$formAction = "courses_create.php";

try {
    $lecturers = (new Lecturer($conn))->all();
} catch (Throwable $exception) {
    $error = "Unable to load lecturers.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach (["code", "name", "credits", "lecturer_id"] as $field) {
        $values[$field] = trim($_POST[$field] ?? "");
    }

    $credits = filter_var(
        $values["credits"],
        FILTER_VALIDATE_INT,
        ["options" => ["min_range" => 1, "max_range" => 255]]
    );
    $lecturerId = null;
    if ($values["lecturer_id"] !== "") {
        $lecturerId = filter_var($values["lecturer_id"], FILTER_VALIDATE_INT);
    }
    $lecturerIds = array_map("intval", array_column($lecturers, "id"));

    if ($error === "" && ($values["code"] === "" || $values["name"] === "" || $credits === false)) {
        $error = "Enter a course code, name, and a credit value from 1 to 255.";
    } elseif (
        $error === ""
        && $values["lecturer_id"] !== ""
        && ($lecturerId === false || !in_array($lecturerId, $lecturerIds, true))
    ) {
        $error = "Select a valid lecturer or leave the course unassigned.";
    }

    if ($error === "") {
        try {
            (new Courses($conn))->create(
                $values["code"],
                $values["name"],
                $credits,
                $lecturerId
            );
            header("Location: courses.php?notice=saved");
            exit;
        } catch (Throwable $exception) {
            $error = "Unable to save course. The course code may already be in use.";
        }
    }
}

define("MVC_REQUEST", true);
require __DIR__ . "/../view/course_form.php";
