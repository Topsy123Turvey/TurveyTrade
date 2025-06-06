<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
file_put_contents('php_errors.log', '');
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

require 'db_connect.php';
session_start();

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $sql = "DELETE FROM products WHERE id = '$product_id'";
    if (mysqli_query($conn, $sql)) {
        header("Location: admin.php");
        exit();
    } else {
        error_log("Delete product failed: " . mysqli_error($conn));
        echo "Error deleting product.";
    }
    mysqli_close($conn);
}
?>