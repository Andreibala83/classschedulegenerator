<?php
$mysqli = new mysqli("localhost", "root", "", "class_schedule");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$day = $_POST['day'] ?? '';
$start_time = $_POST['start_time'] ?? '';
$end_time = $_POST['end_time'] ?? '';
$subject = $_POST['subject'] ?? '';
$room = $_POST['room'] ?? '';
$instructor_title = $_POST['instructor_title'] ?? '';
$instructor = $_POST['instructor'] ?? '';

$stmt = $mysqli->prepare("INSERT INTO schedule (day, start_time, end_time, subject, room, instructor_title, instructor) VALUES (?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) die("Prepare failed: " . $mysqli->error);

$stmt->bind_param("sssssss", $day, $start_time, $end_time, $subject, $room, $instructor_title, $instructor);
$stmt->execute();
$stmt->close();
$mysqli->close();

include 'generate_preview.php';
header("Location: index.php");
exit();
?>
