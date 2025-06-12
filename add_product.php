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
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $image = mysqli_real_escape_string($conn, $_POST['image']);
    $sql = "INSERT INTO products (user_id, name, price, image) VALUES ('$user_id', '$name', '$price', '$image')";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['product_success'] = "Your item '$name' has been listed successfully!";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['product_error'] = "Sorry, we couldn’t list your item. Please try again.";
        header("Location: add_product.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Listing - TurveyTrade</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="welcome-message">
            <h2>List Your Item!</h2>
            <p>Share your item with the TurveyTrade community. Fill in the details below to get started.</p>
        </div>
        <?php if (isset($_SESSION['product_error'])) { ?>
            <p class="error-message"><?php echo htmlspecialchars($_SESSION['product_error']); ?></p>
            <?php unset($_SESSION['product_error']); ?>
        <?php } ?>
        <div class="form-container">
            <h3>Add Product</h3>
            <form action="add_product.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <label for="name">Item Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter item name" required><br><br>
                <label for="price">Price (R):</label>
                <input type="number" id="price" name="price" step="0.01" placeholder="Enter price" required><br><br>
                <label for="image">Image URL:</label>
                <input type="text" id="image" name="image" placeholder="Enter image URL"><br><br>
                <input type="submit" value="Add Product">
            </form>
        </div>
        <p><a href="index.php">Back to Home</a></p>
    </main>
    <?php mysqli_close($conn); ?>
</body>
</html>