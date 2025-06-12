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

// Check if user is logged in and is admin
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

// Delete feedback
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $sql = "DELETE FROM feedback WHERE id = '$id'";
    mysqli_query($conn, $sql);
    header("Location: admin_feedback.php");
    exit();
}

// List feedback
$sql = "SELECT f.id, f.comment, u.name AS user, p.name AS product FROM feedback f JOIN users u ON f.user_id = u.id JOIN products p ON f.product_id = p.id";
$result = mysqli_query($conn, $sql);
?>
<?php include 'header.php'; ?>
<main>
    <h2>Manage Feedback</h2>
    <table>
        <tr><th>ID</th><th>Comment</th><th>User</th><th>Product</th><th>Actions</th></tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['comment']; ?></td>
                <td><?php echo $row['user']; ?></td>
                <td><?php echo $row['product']; ?></td>
                <td><a href="admin_feedback.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete feedback?');">Delete</a></td>
            </tr>
        <?php } ?>
    </table>
    <p><a href="index.php">Back to Home</a></p>
</main>
</body>
</html>