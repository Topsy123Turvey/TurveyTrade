<?php
// Enable error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
file_put_contents('php_errors.log', '');
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

// Include database connection
require 'db_connect.php';
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
    <h1>TurveyTrade</h1>
    <?php
    if (isset($_GET['signup']) && $_GET['signup'] == 'success') {
        echo "<p style='color: green; text-align: center;'>Signed up successfully!</p>";
    }
    ?>
    <h2>Your Profile</h2>
    <form action="profile.php" method="GET">
        <label for="user_id">Enter Your User ID:</label>
        <input type="number" id="user_id" name="user_id" required><br><br>
        <input type="submit" value="View Profile">
    </form>
    <h2>Search Listings</h2>
    <form action="index.php" method="GET">
        <label for="search">Search by Item Name:</label>
        <input type="text" id="search" name="search"><br><br>
        <label for="city">Filter by City:</label>
        <input type="text" id="city" name="city"><br><br>
        <input type="submit" value="Search">
    </form>
    <h2>Sign Up</h2>
    <form action="signup.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        <label for="city">City:</label>
        <input type="text" id="city" name="city" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" value="Sign Up">
        <div class="g-recaptcha" data-sitekey="6LeMX_IqAAAAAPXHG8u6jJzXvAMhoqkP5jJ1fKVO"></div><br>
    </form>
    <h2>List an Item</h2>
    <form action="add_product.php" method="POST">
        <label for="user_id">Your User ID:</label>
        <input type="number" id="user_id" name="user_id" required><br><br>
        <label for="name">Item Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        <label for="price">Price (R):</label>
        <input type="number" id="price" name="price" step="0.01" required><br><br>
        <label for="image">Image URL:</label>
        <input type="text" id="image" name="image"><br><br>
        <input type="submit" value="Add Listing">
    </form>
    <h2>Submit Feedback</h2>
    <form action="add_feedback.php" method="POST">
        <label for="user_id">Your User ID:</label>
        <input type="number" id="user_id" name="user_id" required><br><br>
        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required><br><br>
        <label for="seller_rating">Seller Rating (1-5):</label>
        <input type="number" id="seller_rating" name="seller_rating" min="1" max="5" required><br><br>
        <label for="product_rating">Product Rating (1-5):</label>
        <input type="number" id="product_rating" name="product_rating" min="1" max="5" required><br><br>
        <label for="comment">Comment:</label>
        <textarea id="comment" name="comment" rows="3"></textarea><br><br>
        <input type="submit" value="Submit Feedback">
    </form>
    <h2>Recent Feedback</h2>
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
            echo "<p>No feedback yet!</p>";
        }
        ?>
    </div>
    <div class="listings">
        <?php
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $city = isset($_GET['city']) ? $_GET['city'] : '';
        
        $sql = "SELECT p.id, p.name, p.price, p.image, u.name AS seller, u.city, u.phone 
                FROM products p 
                JOIN users u ON p.user_id = u.id";
        
        $conditions = [];
        if ($search) {
            $conditions[] = "p.name LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
        }
        if ($city) {
            $conditions[] = "u.city LIKE '%" . mysqli_real_escape_string($conn, $city) . "%'";
        }
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='listing'>";
                echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
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
            echo "No listings found!";
        }
        
        // Close connection
        mysqli_close($conn);
        ?>
    </div>
</body>
</html>