<?php
// Controller for creating new records
require_once __DIR__ . "/../model/connection.php";
require_once __DIR__ . "/../model/Lecturer.php";
require_once __DIR__ . "/../model/Courses.php";
require_once __DIR__ . "/../model/Schedules.php";

// Helper function to retrieve and sanitize POST values
function createPostValue(string $key): string
{
    $value = $_POST[$key] ?? "";
    return is_string($value) ? trim($value) : "";
}

// Determine the entity type from the query parameter
$entity = $_GET["entity"] ?? "";
if (!is_string($entity) || !in_array($entity, ["lecturers", "courses", "schedules"], true)) {
    header("Location: index.php"); // Redirect to index if the entity is invalid
    exit;
}

// Initialize values for the selected entity
$values = [
    "lecturers" => [ // Default values for lecturer form
        "name" => "",
        "nidn" => "",
        "phone" => "",
        "join_date" => ""
        ],
    "courses" => [ // Default values for course form
        "code" => "", 
        "name" => "", 
        "credits" => "2", 
        "lecturer_id" => ""
        ],
    "schedules" => [ // Default values for schedule form
        "course_id" => "",
        "day_of_week" => "",
        "start_time" => "",
        "end_time" => "",
        "room" => "",
    ],
][$entity];

// Initialize error message and options for select fields
$error = "";
$options = ["lecturers" => [], "courses" => []];
$days = Schedules::DAYS;

// Load options for select fields based on the entity type
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize POST values for each field
    foreach (array_keys($values) as $field) { 
        $values[$field] = createPostValue($field);
    }

    // Validate and create the record based on the entity type
    if ($entity === "lecturers") {
        if (in_array("", $values, true)) {
            $error = "All lecturer fields are required.";
        } else {
            try { // Attempt to create a new lecturer record
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
        $credits = filter_var( // Validate credits as an integer between 1 and 255
            $values["credits"],
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1, "max_range" => 255]]
        );
        $lecturerId = null; // Initialize lecturerId as null for optional assignment
        $lecturerIds = array_map("intval", array_column($options["lecturers"], "id"));
        if ($values["lecturer_id"] !== "") { // Validate lecturer_id if provided
            $lecturerId = filter_var($values["lecturer_id"], FILTER_VALIDATE_INT);
        }

        // Validate required fields and create the course record
        if ($values["code"] === "" || $values["name"] === "" || $credits === false) {
            $error = "Enter a course code, name, and a credit value from 1 to 255.";
        } elseif (
            // Validate lecturer_id if provided
            $values["lecturer_id"] !== ""
            && ($lecturerId === false || !in_array($lecturerId, $lecturerIds, true))
        ) {
            $error = "Select a valid lecturer or leave the course unassigned.";
        } elseif ($error === "") {
            try { // Attempt to create a new course record
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
    } else { // Handle schedule creation
        $courseId = filter_var($values["course_id"], FILTER_VALIDATE_INT);
        $courseIds = array_map("intval", array_column($options["courses"], "id"));
        $validTimes = preg_match('/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/D', $values["start_time"])
            && preg_match('/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/D', $values["end_time"]);

        // Validate schedule fields and create the schedule record
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

        // Attempt to create a new schedule record if no errors
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

    // Redirect to the read page with a success notice if no errors occurred
    if ($error === "") {
        header("Location: read.php?entity=" . rawurlencode($entity) . "&notice=saved");
        exit;
    }
}

// Prepare form title and action based on the entity type
$titles = [
    "lecturers" => "Create Lecturer",
    "courses" => "Add Course",
    "schedules" => "Add Schedule",
];
$formTitle = $titles[$entity];
$formAction = "create.php?entity=" . rawurlencode($entity);
$lecturers = $options["lecturers"];
$courses = $options["courses"];

// Include the appropriate form view based on the entity type
define("MVC_REQUEST", true);
if ($entity === "lecturers") {
    require __DIR__ . "/../view/lecturer_form.php";
} elseif ($entity === "courses") {
    require __DIR__ . "/../view/course_form.php";
} else {
    require __DIR__ . "/../view/schedule_form.php";
}
