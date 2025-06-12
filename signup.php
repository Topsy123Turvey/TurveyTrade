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

    // Email validation
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        $_SESSION['signup_error'] = "Please enter a valid email address.";
        header("Location: index.php");
        exit();
    }

    // South African phone number validation
    if (!preg_match('/^(\+27|0)[6-8][0-9]{8}$/', $phone)) {
        $_SESSION['signup_error'] = "Please enter a valid South African phone number (e.g., +27612345678).";
        header("Location: index.php");
        exit();
    }

    // Check if email exists
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['signup_error'] = "This email is already registered. Please use a different email or log in.";
        header("Location: index.php");
        exit();
    }

    // Insert new user
    $sql = "INSERT INTO users (name, city, email, phone, password, role) VALUES ('$name', '$city', '$email', '$phone', '$password', '$role')";
    if (mysqli_query($conn, $sql)) {
        $user_id = mysqli_insert_id($conn);
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_role'] = $role;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_city'] = $city;
        $_SESSION['user_phone'] = $phone;
        $_SESSION['user_join_date'] = date('Y-m-d');
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