<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_start();
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

// Enable error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
file_put_contents('php_errors.log', '');
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

require 'db_connect.php';
session_start();

// Check if user is admin (user_id = 1)
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header("Location: login.php");
    exit();
}
?>
<?php include 'header.php'; ?>
<main>
    <h2>Admin Dashboard</h2>
    <p>Welcome, Admin! Manage listings below.</p>
    <h3>All Products</h3>
    <div class="listings">
        <?php
        $sql = "SELECT p.id, p.name, p.price, p.image, u.name AS seller 
                FROM products p 
                JOIN users u ON p.user_id = u.id";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='listing'>";
                echo "<h4>" . htmlspecialchars($row['name']) . "</h4>";
                echo "<p>Price: R" . number_format($row['price'], 2) . "</p>";
                echo "<p>Seller: " . htmlspecialchars($row['seller']) . "</p>";
                if (!empty($row['image'])) {
                    echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' width='100'>";
                }
                echo "<form action='delete_product.php' method='POST'>";
                echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
                echo "<input type='submit' value='Delete' class='report-btn'>";
                echo "</form>";
                echo "</div>";
            }
            mysqli_free_result($result);
        } else {
            echo "<p>No products found.</p>";
        }
        mysqli_close($conn);
        ?>
    </div>
    <p><a href="index.php">Back to Home</a></p>
</main>
</body>
</html>