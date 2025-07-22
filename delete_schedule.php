<?php
$mysqli = new mysqli("localhost", "root", "", "class_schedule");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $mysqli->query("DELETE FROM schedule WHERE id = $id");

    // Re-generate image after deletion
    exec("php generate_image.php");
}

header("Location: index.php?preview=1");
exit;
?>
