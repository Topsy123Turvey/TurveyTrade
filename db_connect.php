<?php
$host = getenv('MYSQLHOST') ?: 'mysql.railway.internal';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$db = getenv('MYSQLDATABASE') ?: 'c2c_db';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>