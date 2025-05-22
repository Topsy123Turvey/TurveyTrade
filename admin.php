<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - TurveyTrade</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Admin - TurveyTrade</h1>
    <h2>Manage Users</h2>

    <!-- Add User -->
    <h3>Add User</h3>
    <form action="admin.php" method="POST">
        <label>Name:</label><input type="text" name="name" required><br>
        <label>Email:</label><input type="email" name="email" required><br>
        <label>City:</label><input type="text" name="city" required><br>
        <label>Role:</label><select name="role"><option value="seller">Seller</option><option value="admin">Admin</option></select><br>
        <input type="submit" name="add" value="Add User">
    </form>

    <!-- User Table -->
    <?php
    include 'db_connect.php';

    if (isset($_POST['add'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $role = mysqli_real_escape_string($conn, $_POST['role']);
        $sql = "INSERT INTO users (name, email, city, role) VALUES ('$name', '$email', '$city', '$role')";
        mysqli_query($conn, $sql);
        header("Location: admin.php");
        exit();
    }

    if (isset($_GET['delete'])) {
        $id = mysqli_real_escape_string($conn, $_GET['delete']);
        $sql = "DELETE FROM users WHERE id = '$id'";
        mysqli_query($conn, $sql);
        header("Location: admin.php");
        exit();
    }

    $sql = "SELECT id, name, email, city, role FROM users";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<table><tr><th>ID</th><th>Name</th><th>Email</th><th>City</th><th>Role</th><th>Actions</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['email']}</td><td>{$row['city']}</td><td>{$row['role']}</td>";
            echo "<td><a href='admin.php?edit={$row['id']}'>Edit</a> | <a href='admin.php?delete={$row['id']}' onclick='return confirm(\"Delete user?\");'>Delete</a></td>";
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
        <form action="admin.php" method="POST">
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
            header("Location: admin.php");
            exit();
        }
    }

    // User Report
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
    <script src="admin.js"></script>
</body>
</html>