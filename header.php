<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_admin = false;
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
    $is_admin = $_SESSION['user_role'] == 'admin';
}
?>
<header>
    <h1>TurveyTrade</h1>
    <div class="profile">
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) { ?>
            <div class="profile-icon" onclick="toggleProfileModal()">
                <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
            </div>
            <div id="profile-modal" class="profile-modal">
                <div class="profile-content">
                    <h3>Your Profile</h3>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
                    <p><strong>City:</strong> <?php echo htmlspecialchars($_SESSION['user_city']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($_SESSION['user_phone']); ?></p>
                    <p><strong>Join Date:</strong> <?php echo htmlspecialchars($_SESSION['user_join_date']); ?></p>
                    <p><strong>Role:</strong> <?php echo htmlspecialchars($_SESSION['user_role']); ?></p>
                    <button onclick="toggleProfileModal()">Close</button>
                </div>
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
        function toggleProfileModal() {
            var modal = document.getElementById('profile-modal');
            modal.style.display = (modal.style.display === 'block') ? 'none' : 'block';
        }
    </script>
</header>