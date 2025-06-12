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
    $sql = "DELETE FROM feedback WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['admin_success'] = "Feedback deleted successfully.";
        header("Location: admin_feedback.php");
        exit();
    } else {
        $_SESSION['admin_error'] = "Failed to delete feedback. Please try again.";
    }
}

$sql = "SELECT f.id, f.comment, u.name AS user, p.name AS product FROM feedback f JOIN users u ON f.user_id = u.id JOIN products p ON f.product_id = p.id";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedback - TurveyTrade</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="welcome-message">
            <h2>Manage Feedback</h2>
            <p>Monitor and manage user feedback to maintain a trustworthy community.</p>
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
                <tr><th>ID</th><th>Comment</th><th>User</th><th>Product</th><th>Actions</th></tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['comment']); ?></td>
                        <td><?php echo htmlspecialchars($row['user']); ?></td>
                        <td><?php echo htmlspecialchars($row['product']); ?></td>
                        <td><a href="admin_feedback.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this feedback?');">Delete</a></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p>No feedback found in the system.</p>
        <?php } ?>
        <p><a href="index.php">Back to Home</a></p>
    </main>
    <?php mysqli_close($conn); ?>
</body>
</html>