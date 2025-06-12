<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback - TurveyTrade</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="welcome-message">
            <h2>Community Feedback</h2>
            <p>Explore what our users are saying about their trading experiences!</p>
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
                    echo "<p>Seller Rating: " . $row['seller_rating'] . "/5</p>";
                    echo "<p>Product Rating: " . $row['product_rating'] . "/5</p>";
                    echo "<p>Comment: " . htmlspecialchars($row['comment']) . "</p>";
                    echo "</div>";
                }
                mysqli_free_result($result);
            } else {
                echo "<p>No feedback has been shared yet. Be the first to leave your thoughts!</p>";
            }
            mysqli_close($conn);
            ?>
        </div>
        <p><a href="add_feedback.php">Submit Your Feedback</a></p>
        <p><a href="index.php">Back to Home</a></p>
    </main>
</body>
</html>