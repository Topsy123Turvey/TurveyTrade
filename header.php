<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_admin = false;
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
    $is_admin = $_SESSION['user_role'] == 'admin';
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
        <div class="profile">
            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) { ?>
                <div class="profile-icon" title="Logged in as <?php echo htmlspecialchars($_SESSION['user_name']); ?> (ID: <?php echo $_SESSION['user_id']; ?>)">
                    <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                </div>
            <?php } ?>
        </div>
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
                <li><a href="feedback.php">View Feedback</a></li>
                <?php if (!isset($_SESSION['user_id'])) { ?>
                    <li><a href="index.php">Sign Up</a></li>
                    <li><a href="index.php">Login</a></li>
                <?php } else { ?>
                    <li><a href="edit_profile.php">Edit Profile</a></li>
                    <li><a href="add_feedback.php">Submit Feedback</a></li>
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