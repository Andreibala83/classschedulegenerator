<?php
$mysqli = new mysqli("localhost", "root", "", "class_schedule");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $day = $_POST['day'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];
    $subject = $_POST['subject'];
    $room = $_POST['room'];

    $stmt = $mysqli->prepare("UPDATE schedule SET day=?, start_time=?, end_time=?, subject=?, room=? WHERE id=?");
    $stmt->bind_param("sssssi", $day, $start, $end, $subject, $room, $id);
    $stmt->execute();
    $stmt->close();

    // Re-generate preview
    exec("php generate_image.php");

    header("Location: index.php?preview=1");
    exit;
}
?>
