<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile - TurveyTrade</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="profile-container">
        <h1>Your Profile - TurveyTrade</h1>
        <?php
        include 'db_connect.php';

        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];

            // User Info
            $sql = "SELECT name, city, email FROM users WHERE id = '$user_id'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                echo "<h2>Welcome, " . $user['name'] . "</h2>";
                echo "<p>City: " . $user['city'] . "</p>";
                echo "<p>Email: " . $user['email'] . "</p>";
            } else {
                echo "<p>User not found!</p>";
            }

            // Listings
            echo "<h3>Your Listings</h3>";
            $sql = "SELECT name, price, image FROM products WHERE user_id = '$user_id'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='listing'>";
                    echo "<h4>" . $row['name'] . "</h4>";
                    echo "<p>Price: R" . $row['price'] . "</p>";
                    echo "<img src='" . $row['image'] . "' alt='" . $row['name'] . "' width='100'>";
                    echo "</div>";
                }
            } else {
                echo "<p>No listings yet!</p>";
            }

            // Feedback Received
            echo "<h3>Feedback Received</h3>";
            $sql = "SELECT f.seller_rating, f.product_rating, f.comment, p.name AS product, u.name AS buyer
                    FROM feedback f
                    JOIN products p ON f.product_id = p.id
                    JOIN users u ON f.user_id = u.id
                    WHERE p.user_id = '$user_id'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='feedback-item'>";
                    echo "<p><strong>" . $row['buyer'] . "</strong> on " . $row['product'] . ":</p>";
                    echo "<p>Seller Rating: " . $row['seller_rating'] . "/5</p>";
                    echo "<p>Product Rating: " . $row['product_rating'] . "/5</p>";
                    echo "<p>Comment: " . $row['comment'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No feedback received yet!</p>";
            }

            mysqli_close($conn);
        } else {
            echo "<p>Please enter a User ID!</p>";
        }
        ?>
        <p><a href="index.php">Back to Home</a></p>
    </div>
</body>
</html>