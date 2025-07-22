<?php
$mysqli = new mysqli("localhost", "root", "", "class_schedule");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $mysqli->query("SELECT * FROM schedule WHERE id = $id");
    $row = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $subject = $_POST['subject'];
    $room = $_POST['room'];
    $id = $_POST['id'];

    $mysqli->query("UPDATE schedule SET day='$day', start_time='$start_time', end_time='$end_time', subject='$subject', room='$room' WHERE id=$id");

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Schedule</title>
    <style>
        body {
            background-color: #000;
            color: white;
            font-family: 'Open Sans', sans-serif;
            text-align: center;
            padding: 30px;
        }
        form {
            margin: auto;
            padding: 20px;
            background-color: #111;
            border-radius: 10px;
            max-width: 400px;
        }
        label {
            display: block;
            margin: 12px 0 6px;
        }
        input, select {
            padding: 8px;
            width: 100%;
            margin-bottom: 10px;
            border: none;
            border-radius: 5px;
        }
        .save-btn {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
        }
        .save-btn:hover {
            background-color: #0056b3;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            color: gold;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <h1>Edit Schedule Entry</h1>

    <form method="post">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">

        <label>Day:</label>
        <select name="day" required>
            <?php
            $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
            foreach ($days as $day) {
                $selected = $row['day'] == $day ? 'selected' : '';
                echo "<option $selected>$day</option>";
            }
            ?>
        </select>

        <label>Start Time:</label>
        <input type="time" name="start_time" value="<?= $row['start_time'] ?>" required>

        <label>End Time:</label>
        <input type="time" name="end_time" value="<?= $row['end_time'] ?>" required>

        <label>Subject:</label>
        <input type="text" name="subject" value="<?= $row['subject'] ?>" required>

        <label>Room:</label>
        <input type="text" name="room" value="<?= $row['room'] ?>" required>

        <button type="submit" class="save-btn">Save Changes</button>
    </form>

    <a class="back-link" href="index.php">← Back to Schedule</a>

</body>
</html>
