<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
include 'db_connect.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email, city, phone FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];

    $email_check = "SELECT id FROM users WHERE email = '$email' AND id != '$user_id'";
    $email_result = mysqli_query($conn, $email_check);
    if (mysqli_num_rows($email_result) > 0) {
        $_SESSION['profile_error'] = "This email is already taken. Please choose a different one.";
        header("Location: edit_profile.php");
        exit();
    } else {
        $sql = "UPDATE users SET name = '$name', email = '$email', city = '$city', phone = '$phone'";
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password = '$password_hash'";
        }
        $sql .= " WHERE id = '$user_id'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['user_name'] = $name;
            $_SESSION['profile_success'] = "Your profile has been updated successfully!";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['profile_error'] = "Sorry, we couldn’t update your profile. Please try again.";
            header("Location: edit_profile.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - TurveyTrade</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="welcome-message">
            <h2>Update Your Profile</h2>
            <p>Keep your information up to date to ensure a smooth trading experience.</p>
        </div>
        <?php if (isset($_SESSION['profile_error'])) { ?>
            <p class="error-message"><?php echo htmlspecialchars($_SESSION['profile_error']); ?></p>
            <?php unset($_SESSION['profile_error']); ?>
        <?php } ?>
        <div class="form-container">
            <h3>Edit Profile</h3>
            <form action="edit_profile.php" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" placeholder="Enter your full name" required><br><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Enter your email" required><br><br>
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city']); ?>" placeholder="Enter your city" required><br><br>
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" placeholder="Enter your phone number"><br><br>
                <label for="password">New Password (leave blank to keep current):</label>
                <input type="password" id="password" name="password" placeholder="Enter new password"><br><br>
                <input type="submit" value="Update Profile">
            </form>
        </div>
        <p><a href="index.php">Back to Home</a></p>
    </main>
    <?php mysqli_close($conn); ?>
</body>
</html>