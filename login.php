<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $sql = "SELECT id, name, role, password FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid password. Please try again.";
            header("Location: index.php");
            exit();
        }
    } else {
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