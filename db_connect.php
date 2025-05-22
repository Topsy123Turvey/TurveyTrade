<?php
$host = "localhost";
$user = "root"; // Default XAMPP user
$pass = "";     // Default XAMPP password (empty)
$db = "c2c_db";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// echo "DB connected"; // Test line—uncomment to check
?>