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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $sql = "SELECT id FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $_SESSION['user_id'] = $user_id;
        header("Location: admin.php");
        exit();
    } else {
        echo "Invalid User ID.";
    }
    mysqli_close($conn);
}
?>
<?php include 'header.php'; ?>
<main>
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <label for="user_id">User ID:</label>
        <input type="number" id="user_id" name="user_id" required><br><br>
        <input type="submit" value="Login">
    </form>
</main>
</body>
</html>