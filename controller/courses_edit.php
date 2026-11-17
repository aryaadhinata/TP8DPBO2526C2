<?php
require_once __DIR__ . "/../model/connection.php";
require_once __DIR__ . "/../model/Courses.php";
require_once __DIR__ . "/../model/Lecturer.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header("Location: courses.php?error=invalid_id");
    exit;
}

$model = new Courses($conn);
$error = "";
$lecturers = [];

try {
    $values = $model->findById($id);
} catch (Throwable $exception) {
    header("Location: courses.php?error=load");
    exit;
}
if ($values === null) {
    header("Location: courses.php?error=not_found");
    exit;
}

try {
    $lecturers = (new Lecturer($conn))->all();
} catch (Throwable $exception) {
    $error = "Unable to load lecturers.";
}

$formTitle = "Edit Course";
$formAction = "courses_edit.php?id=" . urlencode((string) $id);

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
            $model->update($id, $values["code"], $values["name"], $credits, $lecturerId);
            header("Location: courses.php?notice=saved");
            exit;
        } catch (Throwable $exception) {
            $error = "Unable to save course. The course code may already be in use.";
        }
    }
}

define("MVC_REQUEST", true);
require __DIR__ . "/../view/course_form.php";
