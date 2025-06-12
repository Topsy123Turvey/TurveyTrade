<?php
session_start();
$is_admin = false;
if (isset($_SESSION['user_id'])) {
    require 'db_connect.php';
    $sql = "SELECT role FROM users WHERE id = '{$_SESSION['user_id']}'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    if ($user['role'] == 'admin') {
        $is_admin = true;
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TurveyTrade</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <header>
        <h1>TurveyTrade</h1>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <nav class="nav-menu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="search.php">Search Listings</a></li>
                <li><a href="add_product.php">List Item</a></li>
                <?php if (!isset($_SESSION['user_id'])) { ?>
                    <li><a href="signup.php">Sign Up</a></li>
                    <li><a href="login.php">Login</a></li>
                <?php } else { ?>
                    <?php if ($is_admin) { ?>
                        <li><a href="admin_users.php">Manage Users</a></li>
                        <li><a href="admin_products.php">Manage Products</a></li>
                        <li><a href="admin_feedback.php">Manage Feedback</a></li>
                    <?php } ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php } ?>
            </ul>
        </nav>
        <script>
            document.querySelector('.hamburger').addEventListener('click', function() {
                document.querySelector('.nav-menu').classList.toggle('active');
            });
        </script>
    </header>