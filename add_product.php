<?php
// Enable error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
file_put_contents('php_errors.log', '');
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

require 'vendor/autoload.php';
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

Configuration::instance([
    'cloud' => [
        'cloud_name' => getenv('CLOUDINARY_CLOUD_NAME'),
        'api_key' => getenv('CLOUDINARY_API_KEY'),
        'api_secret' => getenv('CLOUDINARY_API_SECRET')
    ],
    'url' => ['secure' => true]
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'db_connect.php';
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $image_url = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        try {
            $upload = (new UploadApi())->upload($_FILES['image']['tmp_name']);
            $image_url = $upload['secure_url'];
        } catch (Exception $e) {
            error_log("Cloudinary upload failed: " . $e->getMessage());
            echo "Error uploading image. Please try again.";
            exit();
        }
    } else {
        echo "Please upload an image.";
        exit();
    }

    $sql = "INSERT INTO products (user_id, name, price, image) VALUES ('$user_id', '$name', '$price', '$image_url')";
    if (mysqli_query($conn, $sql)) {
        header("Location: search.php");
        exit();
    } else {
        error_log("Add product failed: " . mysqli_error($conn));
        echo "Error adding product.";
    }
    mysqli_close($conn);
}
?>
<?php include 'header.php'; ?>
<main>
    <h2>List an Item</h2>
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <label for="user_id">Your User ID:</label>
        <input type="number" id="user_id" name="user_id" required><br><br>
        <label for="name">Item Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        <label for="price">Price (R):</label>
        <input type="number" id="price" name="price" step="0.01" required><br><br>
        <label for="image">Product Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required><br><br>
        <input type="submit" value="Add Listing">
    </form>
</main>
</body>
</html>