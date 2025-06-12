<?php
// Enable error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
file_put_contents('php_errors.log', '');
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    error_log("Attempting login for email: $email");
    $sql = "SELECT id, name, role, password FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        error_log("Stored hash for $email: " . $user['password']);
        error_log("Entered password: $password");
        if (password_verify($password, $user['password'])) {
            error_log("Password verified for $email");
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: index.php");
            exit();
        } else {
            error_log("Password verification failed for $email");
            $_SESSION['login_error'] = "Invalid password. Please try again.";
            header("Location: index.php");
            exit();
        }
    } else {
        error_log("User not found: $email");
        $_SESSION['login_error'] = "User not found. Please check your email or sign up.";
        header("Location: index.php");
        exit();
    }
    mysqli_close($conn);
} else {
    header("Location: index.php");
    exit();
}
?>