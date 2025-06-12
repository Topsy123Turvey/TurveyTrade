<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify reCAPTCHA
    $secret_key = '6Lcfbl4rAAAAAMPOxmadfCsJkwDa92GGG4EL2Ds_';
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$recaptcha_response}");
    $recaptcha = json_decode($verify);

    if (!$recaptcha->success) {
        $_SESSION['login_error'] = "Please complete the reCAPTCHA verification.";
        header("Location: index.php");
        exit();
    }

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $sql = "SELECT id, name, email, city, phone, join_date, role, password FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_city'] = $user['city'];
            $_SESSION['user_phone'] = $user['phone'];
            $_SESSION['user_join_date'] = $user['join_date'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Incorrect password. Please try again.";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Email not found. Please check your email or sign up.";
        header("Location: index.php");
        exit();
    }
    mysqli_close($conn);
} else {
    header("Location: index.php");
    exit();
}
?>