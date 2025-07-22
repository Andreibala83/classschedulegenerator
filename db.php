<?php
$host = 'localhost';         // Usually "localhost"
$user = 'root';              // Default XAMPP username
$password = '';              // Default XAMPP password is empty
$database = 'class_schedule'; // ✅ Change this if your DB name is different

$mysqli = new mysqli($host, $user, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
<?php
$mysqli = new mysqli("localhost", "root", "", "class_schedule");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>

