<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require 'vendor/autoload.php';
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

Configuration::instance([
    'cloud' => [
        'cloud_name' => 'your_cloud_name',
        'api_key' => 'your_api_key',
        'api_secret' => 'your_api_secret'
    ],
    'url' => ['secure' => true]
]);

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $image_url = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload = (new UploadApi())->upload($_FILES['image']['tmp_name']);
        $image_url = $upload['secure_url'];
    } else {
        $_SESSION['product_error'] = "Please upload a valid image.";
        header("Location: add_product.php");
        exit();
    }

    $sql = "INSERT INTO products (user_id, name, price, image) VALUES ('$user_id', '$name', '$price', '$image_url')";
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
    <title>List an Item - TurveyTrade</title>
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
            <form action="add_product.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <label for="name">Item Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter item name" required><br><br>
                <label for="price">Price (R):</label>
                <input type="number" id="price" name="price" step="0.01" placeholder="Enter price" required><br><br>
                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required><br><br>
                <input type="submit" value="Add Listing">
            </form>
        </div>
        <p><a href="index.php">Back to Home</a></p>
    </main>
    <?php mysqli_close($conn); ?>
</body>
</html>