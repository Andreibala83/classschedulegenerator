<!DOCTYPE html>
<html>
<head>
    <title>Class Schedule Preview</title>
</head>
<body style="background-color: #111; color: white; text-align: center; font-family: sans-serif;">
    <h2>Generated Class Schedule</h2>
    <img src="preview.jpg?<?php echo time(); ?>" alt="Class Schedule" style="max-width: 100%; border: 2px solid white;">

    <br><br>

    <a href="preview.jpg?<?php echo time(); ?>" download="class_schedule.jpg">
        <button style="padding: 10px 20px; font-size: 18px;">Download JPEG</button>
    </a>

    <br><br>
    <a href="index.php">
        <button style="padding: 10px 20px; font-size: 16px;">Back to Edit</button>
    </a>
</body>
</html>
