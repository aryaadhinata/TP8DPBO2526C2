<?php
require_once __DIR__ . "/../model/connection.php";
require_once __DIR__ . "/../model/Schedules.php";

$schedules = [];
$error = "";

try {
    $schedules = (new Schedules($conn))->all();
} catch (Throwable $exception) {
    $error = "Unable to load schedules.";
}

define("MVC_REQUEST", true);
require __DIR__ . "/../view/schedules.php";
