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

if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $sql = "INSERT INTO users (name, email, city, phone, password, role) VALUES ('$name', '$email', '$city', '$phone', '$password', '$role')";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['admin_success'] = "User '$name' has been added successfully!";
        header("Location: admin_users.php");
        exit();
    } else {
        $_SESSION['admin_error'] = "Failed to add user. Please try again.";
    }
}

if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $sql = "DELETE FROM users WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['admin_success'] = "User deleted successfully.";
        header("Location: admin_users.php");
        exit();
    } else {
        $_SESSION['admin_error'] = "Failed to delete user. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - TurveyTrade</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="welcome-message">
            <h2>Manage Users</h2>
            <p>As an admin, you can add, edit, or delete users to keep the community safe and thriving.</p>
        </div>
        <?php if (isset($_SESSION['admin_error'])) { ?>
            <p class="error-message"><?php echo htmlspecialchars($_SESSION['admin_error']); ?></p>
            <?php unset($_SESSION['admin_error']); ?>
        <?php } ?>
        <?php if (isset($_SESSION['admin_success'])) { ?>
            <p class="success-message"><?php echo htmlspecialchars($_SESSION['admin_success']); ?></p>
            <?php unset($_SESSION['admin_success']); ?>
        <?php } ?>
        <div class="form-container">
            <h3>Add User</h3>
            <form action="admin_users.php" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter full name" required><br><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter email" required><br><br>
                <label for="city">City:</label>
                <input type="text" id="city" name="city" placeholder="Enter city" required><br><br>
                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter phone number"><br><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required><br><br>
                <label for="role">Role:</label>
                <select id="role" name="role">
                    <option value="seller">Seller</option>
                    <option value="admin">Admin</option>
                </select><br><br>
                <input type="submit" name="add" value="Add User">
            </form>
        </div>
        <?php
        $sql = "SELECT id, name, email, city, phone, role FROM users";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "<table><tr><th>ID</th><th>Name</th><th>Email</th><th>City</th><th>Phone</th><th>Role</th><th>Actions</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['id']}</td><td>" . htmlspecialchars($row['name']) . "</td><td>" . htmlspecialchars($row['email']) . "</td><td>" . htmlspecialchars($row['city']) . "</td><td>" . htmlspecialchars($row['phone']) . "</td><td>" . htmlspecialchars($row['role']) . "</td>";
                echo "<td><a href='admin_users.php?edit={$row['id']}'>Edit</a> | <a href='admin_users.php?delete={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No users found in the system.</p>";
        }

        if (isset($_GET['edit'])) {
            $id = mysqli_real_escape_string($conn, $_GET['edit']);
            $sql = "SELECT name, email, city, phone, role FROM users WHERE id = '$id'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_assoc($result);
            ?>
            <div class="form-container">
                <h3>Edit User ID: <?php echo $id; ?></h3>
                <form action="admin_users.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" placeholder="Enter full name" required><br><br>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Enter email" required><br><br>
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city']); ?>" placeholder="Enter city" required><br><br>
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" placeholder="Enter phone number"><br><br>
                    <label for="role">Role:</label>
                    <select id="role" name="role">
                        <option value="seller" <?php if ($user['role'] == 'seller') echo 'selected'; ?>>Seller</option>
                        <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                    </select><br><br>
                    <input type="submit" name="update" value="Update User">
                </form>
            </div>
            <?php
            if (isset($_POST['update'])) {
                $id = mysqli_real_escape_string($conn, $_POST['id']);
                $name = mysqli_real_escape_string($conn, $_POST['name']);
                $email = mysqli_real_escape_string($conn, $_POST['email']);
                $city = mysqli_real_escape_string($conn, $_POST['city']);
                $phone = mysqli_real_escape_string($conn, $_POST['phone']);
                $role = mysqli_real_escape_string($conn, $_POST['role']);
                $sql = "UPDATE users SET name='$name', email='$email', city='$city', phone='$phone', role='$role' WHERE id='$id'";
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['admin_success'] = "User '$name' has been updated successfully!";
                    header("Location: admin_users.php");
                    exit();
                } else {
                    $_SESSION['admin_error'] = "Failed to update user. Please try again.";
                }
            }
        }
        ?>
        <div class="welcome-message">
            <h3>User Report</h3>
            <p>Summary of user roles in the system.</p>
        </div>
        <?php
        $sql = "SELECT role, COUNT(*) as count FROM users GROUP BY role";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "<table><tr><th>Role</th><th>Count</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . htmlspecialchars($row['role']) . "</td><td>{$row['count']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No data available for the report.</p>";
        }
        mysqli_close($conn);
        ?>
        <p><a href="index.php">Back to Home</a></p>
    </main>
</body>
</html>