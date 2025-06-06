<?php
// Enable error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
file_put_contents('php_errors.log', '');
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

require 'db_connect.php';
session_start();

// Check if user is admin
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$sql = "SELECT role FROM users WHERE id = '{$_SESSION['user_id']}'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
if ($user['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Delete product
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $sql = "DELETE FROM products WHERE id = '$id'";
    mysqli_query($conn, $sql);
    header("Location: admin_products.php");
    exit();
}

// List products
$sql = "SELECT p.id, p.name, p.price, u.name AS seller FROM products p JOIN users u ON p.user_id = u.id";
$result = mysqli_query($conn, $sql);
?>
<?php include 'header.php'; ?>
<main>
    <h2>Manage Products</h2>
    <table>
        <tr><th>ID</th><th>Name</th><th>Price</th><th>Seller</th><th>Actions</th></tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['seller']; ?></td>
                <td><a href="admin_products.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete product?');">Delete</a></td>
            </tr>
        <?php } ?>
    </table>
    <p><a href="index.php">Back to Home</a></p>
</main>
</body>
</html>