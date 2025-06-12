<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $seller_rating = mysqli_real_escape_string($conn, $_POST['seller_rating']);
    $product_rating = mysqli_real_escape_string($conn, $_POST['product_rating']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $sql = "INSERT INTO feedback (user_id, product_id, seller_rating, product_rating, comment) 
            VALUES ('$user_id', '$product_id', '$seller_rating', '$product_rating', '$comment')";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['feedback_success'] = "Thank you for your feedback! It’s been shared successfully.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['feedback_error'] = "Sorry, we couldn’t submit your feedback. Please try again.";
        header("Location: add_feedback.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback - TurveyTrade</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="welcome-message">
            <h2>Share Your Feedback!</h2>
            <p>Let us know about your experience with the seller and product. Your input helps our community thrive!</p>
        </div>
        <?php if (isset($_SESSION['feedback_error'])) { ?>
            <p class="error-message"><?php echo htmlspecialchars($_SESSION['feedback_error']); ?></p>
            <?php unset($_SESSION['feedback_error']); ?>
        <?php } ?>
        <div class="form-container">
            <h3>Submit Feedback</h3>
            <form action="add_feedback.php" method="POST">
                <label for="product_id">Product ID:</label>
                <input type="number" id="product_id" name="product_id" placeholder="Enter product ID" required><br><br>
                <label for="seller_rating">Seller Rating (1–5):</label>
                <input type="number" id="seller_rating" name="seller_rating" min="1" max="5" placeholder="4" required><br><br>
                <label for="product_rating">Product Rating (1–5):</label>
                <input type="number" id="product_rating" name="product_rating" min="1" max="5" placeholder="4" required><br><br>
                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment" rows="4" placeholder="Share your thoughts"></textarea><br><br>
                <input type="submit" value="Submit Feedback">
            </form>
        </div>
        <p><a href="index.php">Back to Home</a></p>
    </main>
    <?php mysqli_close($conn); ?>
</body>
</html>