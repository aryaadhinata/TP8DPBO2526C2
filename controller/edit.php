<?php
require_once __DIR__ . "/../model/connection.php";
require_once __DIR__ . "/../model/Lecturer.php";

$error = "";
$lecturer = [
    "id" => "",
    "name" => "",
    "nidn" => "",
    "phone" => "",
    "join_date" => "",
];
$model = new Lecturer($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    foreach (["name", "nidn", "phone", "join_date"] as $field) {
        $lecturer[$field] = trim($_POST[$field] ?? "");
    }
    $lecturer["id"] = $id ?: "";

    if (!$id || in_array("", array_slice($lecturer, 1), true)) {
        $error = "All fields are required.";
    } else {
        try {
            $model->update(
                $id,
                $lecturer["name"],
                $lecturer["nidn"],
                $lecturer["phone"],
                $lecturer["join_date"]
            );
            header("Location: lecturers.php");
            exit;
        } catch (Throwable $exception) {
            $error = "Unable to update lecturer.";
        }
    }
} else {
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    if (!$id) {
        header("Location: lecturers.php");
        exit;
    }

    try {
        $lecturer = $model->findById($id);
    } catch (Throwable $exception) {
        $error = "Unable to load lecturer.";
    }

    if ($lecturer === null) {
        header("Location: lecturers.php");
        exit;
    }
}

define("MVC_REQUEST", true);
require __DIR__ . "/../view/edit.php";
