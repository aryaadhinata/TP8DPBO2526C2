<?php
require_once __DIR__ . "/../model/connection.php";
require_once __DIR__ . "/../model/Lecturer.php";

$error = "";
$values = [
    "name" => "",
    "nidn" => "",
    "phone" => "",
    "join_date" => "",
];

if (isset($_POST["submit"])) {
    foreach ($values as $field => $value) {
        $values[$field] = trim($_POST[$field] ?? "");
    }

    if (in_array("", $values, true)) {
        $error = "All fields are required.";
    } else {
        try {
            (new Lecturer($conn))->create(
                $values["name"],
                $values["nidn"],
                $values["phone"],
                $values["join_date"]
            );
            header("Location: index.php");
            exit;
        } catch (Throwable $exception) {
            $error = "Unable to create lecturer.";
        }
    }
}

define("MVC_REQUEST", true);
require __DIR__ . "/../view/create.php";
