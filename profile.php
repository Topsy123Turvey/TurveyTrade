<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - TurveyTrade</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="welcome-message">
            <h2>Your Profile</h2>
            <p>Here’s your account information and your active listings.</p>
        </div>
        <?php
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT name, email, city, phone, join_date, role FROM users WHERE id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        if ($user = mysqli_fetch_assoc($result)) {
            echo "<div class='form-container'>";
            echo "<h3>Account Details</h3>";
            echo "<p><strong>Name:</strong> " . htmlspecialchars($user['name']) . "</p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($user['email']) . "</p>";
            echo "<p><strong>City:</strong> " . htmlspecialchars($user['city']) . "</p>";
            echo "<p><strong>Phone:</strong> " . htmlspecialchars($user['phone']) . "</p>";
            echo "<p><strong>Join Date:</strong> " . htmlspecialchars($user['join_date']) . "</p>";
            echo "<p><strong>Role:</strong> " . htmlspecialchars($user['role']) . "</p>";
            echo "</div>";
        }
        ?>
        <div class="welcome-message">
            <h3>Your Listings</h3>
            <p>View all the items you’ve listed for sale.</p>
        </div>
        <?php
        $sql = "SELECT id, name, price, image FROM products WHERE user_id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            echo "<table><tr><th>ID</th><th>Name</th><th>Price</th><th>Image</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['id']}</td><td>" . htmlspecialchars($row['name']) . "</td><td>R{$row['price']}</td>";
                echo "<td><img src='" . htmlspecialchars($row['image']) . "' alt='Product Image' width='50'></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>You haven’t listed any items yet.</p>";
        }
        mysqli_close($conn);
        ?>
        <p><a href="index.php">Back to Home</a></p>
    </main>
</body>
</html>