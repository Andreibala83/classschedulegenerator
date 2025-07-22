<!DOCTYPE html>
<html>
<head>
    <title>Class Schedule Wallpaper Generator</title>
    <style>
        body {
            background-color: #000;
            color: white;
            font-family: 'Open Sans', sans-serif;
            text-align: center;
            padding: 20px;
        }
        form {
            margin-bottom: 30px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, select {
            padding: 5px;
            margin-bottom: 10px;
            width: 200px;
        }
        .submit-btn, .generate-btn, .edit-btn, .delete-btn {
            padding: 10px 20px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            margin-top: 10px;
        }
        .submit-btn {
            background-color: #28a745;
            color: white;
        }
        .generate-btn {
            background-color: gold;
            color: black;
        }
        .edit-btn {
            background-color: #007bff;
            color: white;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        table {
            margin: auto;
            border-collapse: collapse;
            width: 90%;
            color: white;
        }
        th, td {
            border: 1px solid #666;
            padding: 8px;
        }
        img.preview {
            border: 2px solid #fff;
            margin-top: 10px;
            max-width: 90%;
        }
    </style>
</head>
<body>
    <h1>Class Schedule Wallpaper Generator</h1>

    <!-- Schedule Input Form -->
    <form action="save_schedule.php" method="post">
        <label>Day:</label>
        <select name="day" required>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
        </select>

        <label>Start Time:</label>
        <input type="time" name="start_time" required>

        <label>End Time:</label>
        <input type="time" name="end_time" required>

        <label>Subject:</label>
        <input type="text" name="subject" required>

        <label>Room:</label>
        <input type="text" name="room" required>

        <label>Instructor Title:</label>
        <select name="instructor_title" required>
            <option value="Mr.">Mr.</option>
            <option value="Ms.">Ms.</option>
            <option value="Mrs.">Mrs.</option>
        </select>

        <label>Instructor Name:</label>
        <input type="text" name="instructor" placeholder="e.g. Juan Dela Cruz" required>

        <br>
        <button type="submit" class="submit-btn">Add to Schedule</button>
    </form>

    <!-- Generate Wallpaper Form with Background Upload -->
    <form action="generate_image.php" method="post" enctype="multipart/form-data">
        <label>Select Background Image (JPG):</label>
        <input type="file" name="custom_bg" accept=".jpg,.jpeg">
        <br>
        <button type="submit" class="generate-btn">Generate Wallpaper</button>
    </form>

    <!-- Preview Link -->
    <div style="margin-top: 20px;">
        <a href="preview.php" class="generate-btn">View Schedule Preview</a>
    </div>

    <!-- View All Schedule Entries -->
    <h2>My Schedule Entries</h2>
    <table>
        <tr>
            <th>Day</th>
            <th>Time</th>
            <th>Subject</th>
            <th>Room</th>
            <th>Instructor</th>
            <th>Actions</th>
        </tr>
        <?php
        $mysqli = new mysqli("localhost", "root", "", "class_schedule");
        $result = $mysqli->query("SELECT * FROM schedule ORDER BY FIELD(day, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'), start_time");

        while ($row = $result->fetch_assoc()):
            $instructorTitle = $row['instructor_title'] ?? '';
            $instructorName = $row['instructor'] ?? '';
        ?>
       <tr>
    <td><?= $row['day'] ?></td>
    <td><?= $row['start_time'] ?> - <?= $row['end_time'] ?></td> <!-- Time first -->
    <td><?= $row['subject'] ?></td> <!-- Then Subject -->
    <td><?= $row['room'] ?></td>
    <td><?= $instructorTitle . ' ' . $instructorName ?></td>
    <td>
        <a href="edit_schedule.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
        <a href="delete_schedule.php?id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this entry?')">Delete</a>
    </td>
</tr>

        <?php endwhile; ?>
    </table>
</body>
<footer style="text-align: center; margin-top: 40px; color: gray; font-size: 14px;">
    <p>Created by Andrei Bala &mdash; Class Schedule Wallpaper Generator (codes from ChatGPT) &copy; 2025</p>
</footer>
</html>
