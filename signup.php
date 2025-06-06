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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'db_connect.php';
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (name, city, email, phone, password) VALUES ('$name', '$city', '$email', '$phone', '$password')";
    if (mysqli_query($conn, $sql)) {
        $user_id = mysqli_insert_id($conn); // Get the new user's ID
        session_start();
        $_SESSION['user_id'] = $user_id;
        header("Location: index.php?signup=success");
        exit();
    } else {
        error_log("Signup failed: " . mysqli_error($conn));
        echo "Error signing up: " . htmlspecialchars(mysqli_error($conn));
    }
    mysqli_close($conn);
}
?>
<?php include 'header.php'; ?>
<main>
    <h2>Sign Up</h2>
    <form action="signup.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        <label for="city">City:</label>
        <input type="text" id="city" name="city" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Sign Up">
        <div class="g-recaptcha" data-sitekey="6Lf06FYrAAAAAEwp3Q7nJPfsscMET71xbnvqAWjM"></div><br>
    </form>
    <p>After signing up, your unique ID will be assigned automatically!</p>
</main>
</body>
</html>