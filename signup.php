<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recaptcha_secret = "6LeMX_IqAAAAACj8xePGYJyobYDzsQH9twVWaUxc";
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
    $captcha_success = json_decode($verify);

    if ($captcha_success->success) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $join_date = date('Y-m-d'); // No escape needed - safe

        // Check for duplicate email
        $email_check = "SELECT id FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $email_check);
        if (mysqli_num_rows($result) > 0) {
            echo "Email already taken!";
            mysqli_close($conn);
            exit();
        }

        $sql = "INSERT INTO users (name, city, join_date, email) 
                VALUES ('$name', '$city', '$join_date', '$email')";
        
        if (mysqli_query($conn, $sql)) {
            mysqli_close($conn);
            header("Location: index.php?signup=success");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "CAPTCHA verification failed!";
    }
    mysqli_close($conn);
}
?>