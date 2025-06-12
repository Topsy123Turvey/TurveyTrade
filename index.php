<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db_connect.php';
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
    <?php include 'header.php'; ?>
    <main>
        <?php if (!isset($_SESSION['user_id'])) { ?>
            <div class="welcome-message">
                <h2>Welcome to TurveyTrade!</h2>
                <p>Please log in to access your account and start trading.</p>
            </div>
            <?php if (isset($_SESSION['login_error'])) { ?>
                <p class="error-message"><?php echo htmlspecialchars($_SESSION['login_error']); ?></p>
                <?php unset($_SESSION['login_error']); ?>
            <?php } ?>
            <div class="form-container">
                <h3>Login</h3>
                <form action="login.php" method="POST">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required><br><br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required><br><br>
                    <input type="submit" value="Login">
                </form>
                <p class="signup-prompt">Not signed up yet? <a href="#" onclick="showSignup()">Click here to sign up!</a></p>
                <div id="signup-form" style="display: none;">
                    <h3>Sign Up</h3>
                    <form action="signup.php" method="POST">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Enter your full name" required><br><br>
                        <label for="city">City:</label>
                        <input type="text" id="city" name="city" placeholder="Enter your city" required><br><br>
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required><br><br>
                        <label for="phone">Phone Number:</label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required><br><br>
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" placeholder="Choose a secure password" required><br><br>
                        <input type="submit" value="Sign Up">
                        <div class="feedback" data-gotcha="{{ feedback_form.gotcha_field }}"></div>
                        <div class="g-recaptcha" data-sitekey="your_site_key_here"></div><br>
                    </form>
                </div>
            </div>
            <script>
                function showSignup() {
                    document.getElementById('signup-form').style.display = 'block';
                }
            </script>
        <?php } else { ?>
            <div class="welcome-message">
                <h2>Welcome back, {{ $_SESSION['user_name'] }}!</h2>
                <p>Ready to trade? Explore the marketplace, list your items, or share your feedback!</p>
                <?php if (isset($_SESSION['signup_success'])) { ?>
                    <p class="success-message"><?php echo htmlspecialchars($_SESSION['signup_success']); ?></p>
                    <?php unset($_SESSION['signup_success']); ?>
                <?php } ?>
                <?php if (isset($_SESSION['feedback_success'])) { ?>
                    <p class="success-message"><?php echo htmlspecialchars($_SESSION['feedback_success']); ?></p>
                    <?php unset($_SESSION['feedback_success']); ?>
                <?php } ?>
            </div>
            <!-- Search Listings -->
            <div class="form-container">
                <h3>Search Listings</h3>
                <form action="search.php" method="GET">
                    <label for="search">Search by Item Name:</label>
                    <input type="text" id="search" name="search" placeholder="Enter item name"><br><br>
                    <label for="city">Filter by City:</label>
                    <input type="text" id="city" name="city" placeholder="Enter city"><br><br>
                    <input type="submit" value="Search">
                </form>
            </div>
            <!-- List an Item -->
            <div class="form-container">
                <h3>List an Item</h3>
                <form action="add_product.php" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <label for="name">Item Name:</label>
                    <input type="text" id="name" name="name" placeholder="Enter item name" required><br><br>
                    <label for="price">Price (R):</label>
                    <input type="number" id="price" name="price" step="0.01" placeholder="Enter price" required><br><br>
                    <label for="image">Image URL:</label>
                    <input type="text" id="image" name="image" placeholder="Enter image URL"><br><br>
                    <input type="submit" value="Add Listing">
                </form>
            </div>
            <!-- Submit Feedback -->
            <div class="form-container">
                <h3>Submit Feedback</h3>
                <form action="add_feedback.php" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <label for="product_id">Product ID:</label>
                    <input type="number" id="product_id" name="product_id" placeholder="Enter product ID" required><br><br>
                    <label for="seller_rating">Seller Rating (1–5):</label>
                    <input type="number" id="seller_rating" name="seller_rating" min="1" max="5" placeholder="4" required><br><br>
                    <label for="product_rating">Product Rating (1–5):</label>
                    <input type="number" id="product_rating" name="product_rating" min="1" max="5" placeholder="4" required><br><br>
                    <label for="comment">Comment:</label>
                    <textarea id="comment" name="comment" rows="3" placeholder="Share your thoughts"></textarea><br><br>
                    <input type="submit" value="Submit Feedback">
                </form>
            </div>
            <!-- Recent Feedback -->
            <div class="welcome-message">
                <h3>Recent Feedback</h3>
                <p>Check out what our community is saying!</p>
            </div>
            <div class="feedback">
                <?php
                $sql = "SELECT f.seller_rating, f.product_rating, f.comment, 
                               u.name AS buyer, p.name AS product
                        FROM feedback f
                        JOIN users u ON f.user_id = u.id
                        JOIN products p ON f.product_id = p.id";
                $result = mysqli_query($conn, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='feedback-item'>";
                        echo "<p><strong>" . htmlspecialchars($row['buyer']) . "</strong> on " . htmlspecialchars($row['product']) . ":</p>";
                        echo "<p>Seller: " . $row['seller_rating'] . "/5</p>";
                        echo "<p>Product: " . $row['product_rating'] . "/5</p>";
                        echo "<p>Comment: " . htmlspecialchars($row['comment']) . "</p>";
                        echo "</div>";
                    }
                    mysqli_free_result($result);
                } else {
                    echo "<p>No feedback yet! Be the first to share your experience.</p>";
                }
                ?>
            </div>
            <!-- Listings -->
            <div class="welcome-message">
                <h3>Explore Listings</h3>
                <p>Browse the latest items available for trade.</p>
            </div>
            <div class="listings">
                <?php
                $sql = "SELECT p.id, p.name, p.price, p.image, u.name AS seller, u.city, u.phone 
                        FROM products p 
                        JOIN users u ON p.user_id = u.id";
                $result = mysqli_query($conn, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='listing'>";
                        echo "<h4>" . htmlspecialchars($row['name']) . "</h4>";
                        echo "<p>Price: R" . number_format($row['price'], 2) . "</p>";
                        echo "<p>Seller: " . htmlspecialchars($row['seller']) . " (" . htmlspecialchars($row['city']) . ")</p>";
                        echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' width='100'>";
                        echo "<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_blank'>";
                        echo "<input type='hidden' name='cmd' value='_xclick'>";
                        echo "<input type='hidden' name='business' value='your_sandbox_email@example.com'>";
                        echo "<input type='hidden' name='item_name' value='" . htmlspecialchars($row['name']) . "'>";
                        echo "<input type='hidden' name='amount' value='" . $row['price'] . "'>";
                        echo "<input type='hidden' name='currency_code' value='ZAR'>";
                        echo "<input type='submit' value='Buy Now' class='paypal-btn'>";
                        echo "</form>";
                        echo "<a href='https://wa.me/" . htmlspecialchars($row['phone']) . "?text=Hi%20" . htmlspecialchars($row['seller']) . ",%20I’m%20interested%20in%20your%20" . htmlspecialchars($row['name']) . "' target='_blank' class='whatsapp-btn'>Contact Seller</a>";
                        echo "</div>";
                    }
                    mysqli_free_result($result);
                } else {
                    echo "<p>No listings found! Check back soon or list your own item.</p>";
                }
                mysqli_close($conn);
                ?>
            </div>
        <?php } ?>
    </main>
</body>
</html>