<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
include 'db_connect.php';
$sql = "SELECT role FROM users WHERE id = '{$_SESSION['user_id']}'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
if ($user['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $sql = "DELETE FROM products WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['admin_success'] = "Product deleted successfully.";
        header("Location: admin_products.php");
        exit();
    } else {
        $_SESSION['admin_error'] = "Failed to delete product. Please try again.";
    }
}

$sql = "SELECT p.id, p.name, p.price, u.name AS seller FROM products p JOIN users u ON p.user_id = u.id";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - TurveyTrade</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="welcome-message">
            <h2>Manage Products</h2>
            <p>Review and manage all product listings to ensure a quality marketplace.</p>
        </div>
        <?php if (isset($_SESSION['admin_error'])) { ?>
            <p class="error-message"><?php echo htmlspecialchars($_SESSION['admin_error']); ?></p>
            <?php unset($_SESSION['admin_error']); ?>
        <?php } ?>
        <?php if (isset($_SESSION['admin_success'])) { ?>
            <p class="success-message"><?php echo htmlspecialchars($_SESSION['admin_success']); ?></p>
            <?php unset($_SESSION['admin_success']); ?>
        <?php } ?>
        <?php if (mysqli_num_rows($result) > 0) { ?>
            <table>
                <tr><th>ID</th><th>Name</th><th>Price</th><th>Seller</th><th>Actions</th></tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td>R<?php echo number_format($row['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($row['seller']); ?></td>
                        <td><a href="admin_products.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p>No products found in the system.</p>
        <?php } ?>
        <p><a href="index.php">Back to Home</a></p>
    </main>
    <?php mysqli_close($conn); ?>
</body>
</html>