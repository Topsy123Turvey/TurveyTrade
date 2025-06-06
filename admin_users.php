<?php
// Enable error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
file_put_contents('php_errors.log', '');
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

require 'db_connect.php';

// Temporarily disable admin check for screenshots
/*
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
*/

if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $sql = "INSERT INTO users (name, email, city, role) VALUES ('$name', '$email', '$city', '$role')";
    mysqli_query($conn, $sql);
    header("Location: admin_users.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $sql = "DELETE FROM users WHERE id = '$id'";
    mysqli_query($conn, $sql);
    header("Location: admin_users.php");
    exit();
}

$sql = "SELECT id, name, email, city, role FROM users";
$result = mysqli_query($conn, $sql);
?>
<?php include 'header.php'; ?>
<main>
    <h2>Manage Users</h2>
    <h3>Add User</h3>
    <form action="admin_users.php" method="POST">
        <label>Name:</label><input type="text" name="name" required><br>
        <label>Email:</label><input type="email" name="email" required><br>
        <label>City:</label><input type="text" name="city" required><br>
        <label>Role:</label><select name="role"><option value="seller">Seller</option><option value="admin">Admin</option></select><br>
        <input type="submit" name="add" value="Add User">
    </form>
    <h3>User Table</h3>
    <?php
    if (mysqli_num_rows($result) > 0) {
        echo "<table><tr><th>ID</th><th>Name</th><th>Email</th><th>City</th><th>Role</th><th>Actions</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['email']}</td><td>{$row['city']}</td><td>{$row['role']}</td>";
            echo "<td><a href='admin_users.php?edit={$row['id']}'>Edit</a> | <a href='admin_users.php?delete={$row['id']}' onclick='return confirm(\"Delete user?\");'>Delete</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No users found!</p>";
    }

    if (isset($_GET['edit'])) {
        $id = mysqli_real_escape_string($conn, $_GET['edit']);
        $sql = "SELECT name, email, city, role FROM users WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($result);
        ?>
        <h3>Edit User ID: <?php echo $id; ?></h3>
        <form action="admin_users.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <label>Name:</label><input type="text" name="name" value="<?php echo $user['name']; ?>" required><br>
            <label>Email:</label><input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>
            <label>City:</label><input type="text" name="city" value="<?php echo $user['city']; ?>" required><br>
            <label>Role:</label><select name="role">
                <option value="seller" <?php if ($user['role'] == 'seller') echo 'selected'; ?>>Seller</option>
                <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
            </select><br>
            <input type="submit" name="update" value="Update User">
        </form>
        <?php
        if (isset($_POST['update'])) {
            $id = mysqli_real_escape_string($conn, $_POST['id']);
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $city = mysqli_real_escape_string($conn, $_POST['city']);
            $role = mysqli_real_escape_string($conn, $_POST['role']);
            $sql = "UPDATE users SET name='$name', email='$email', city='$city', role='$role' WHERE id='$id'";
            mysqli_query($conn, $sql);
            header("Location: admin_users.php");
            exit();
        }
    }
    ?>
    <h2>User Report</h2>
    <?php
    $sql = "SELECT role, COUNT(*) as count FROM users GROUP BY role";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<table><tr><th>Role</th><th>Count</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>{$row['role']}</td><td>{$row['count']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No data for report!</p>";
    }
    mysqli_close($conn);
    ?>
    <p><a href="index.php">Back to Home</a></p>
</main>
</body>
</html>