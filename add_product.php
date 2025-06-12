<?php


session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
include 'db_connect.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    $price = $_POST['price'];
    $image_url = '';

    error_log("POST data: user_id=$user_id, name=$name, price=$price");
    error_log("FILES array: " . print_r($_FILES, true));

    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        try {
            $upload = (new UploadApi())->upload($_FILES['image']['tmp_name'], [
                'folder' => 'turveytrade'
            ]);
            $image_url = $upload['secure_url'];
            error_log("Cloudinary upload success: $image_url");
        } catch (Exception $e) {
            error_log("Cloudinary upload failed: " . $e->getMessage());
            echo "Error uploading image: " . htmlspecialchars($e->getMessage());
            exit();
        }
    } else {
        error_log("Image upload error code: " . ($_FILES['image']['error'] ?? 'No file uploaded'));
        echo "Please upload a valid image (error code: " . ($_FILES['image']['error'] ?? 'No file') . ")";
        exit();
    }

    $sql = "INSERT INTO products (user_id, name, price, image) VALUES ('$user_id', '$name', '$price', '$image_url')";
    if (mysqli_query($conn, $sql)) {
        error_log("Product added: $name");
        header("Location: search.php");
        exit();
    } else {
        error_log("Add product failed: " . mysqli_error($conn));
        echo "Error adding product: " . htmlspecialchars(mysqli_error($conn));
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