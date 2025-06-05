<?php
$host = getenv('MYSQLHOST') ?: 'mysql.railway.internal';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: 'AkucHpkwrWGxDwUnVicCKAIyoKLxOMtL';
$db = 'c2c_db';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    error_log("Connection failed: " . mysqli_connect_error());
    die("Database connection error. Please try again later.");
}
?>