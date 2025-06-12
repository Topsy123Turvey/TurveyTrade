<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'seller';

    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['signup_error'] = "This email is already registered. Please use a different email or log in.";
        header("Location: index.php");
        exit();
    }

    $sql = "INSERT INTO users (name, city, email, phone, password, role) VALUES ('$name', '$city', '$email', '$phone', '$password', '$role')";
    if (mysqli_query($conn, $sql)) {
        $user_id = mysqli_insert_id($conn);
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_role'] = $role;
        $_SESSION['signup_success'] = "Welcome to TurveyTrade, $name! Your account has been created successfully.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['signup_error'] = "Sorry, something went wrong. Please try signing up again.";
        header("Location: index.php");
        exit();
    }
    mysqli_close($conn);
} else {
    header("Location: index.php");
    exit();
}
?>