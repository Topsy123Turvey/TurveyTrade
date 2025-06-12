<?php
session_start();
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

    // Check if email is unique, excluding current user
    $email_check = "SELECT id FROM users WHERE email = '$email' AND id != '$user_id'";
    $email_result = mysqli_query($conn, $email_check);
    if (mysqli_num_rows($email_result) > 0) {
        echo "Email already taken!";
    } else {
        $sql = "UPDATE users SET name = '$name', email = '$email', city = '$city', phone = '$phone'";
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password = '$password_hash'";
        }
        $sql .= " WHERE id = '$user_id'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['user_name'] = $name; // Update session name
            header("Location: index.php?profile=updated");
            exit();
        } else {
            echo "Error updating profile: " . mysqli_error($conn);
        }
    }
}
mysqli_close($conn);
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
        <h2>Edit Profile</h2>
        <form action="edit_profile.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>
            <label for="city">City:</label>
            <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city']); ?>" required><br><br>
            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"><br><br>
            <label for="password">New Password (leave blank to keep current):</label>
            <input type="password" id="password" name="password"><br><br>
            <input type="submit" value="Update Profile">
        </form>
        <p><a href="index.php">Back to Home</a></p>
    </main>
</body>
</html>