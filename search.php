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
    <title>Search Listings - TurveyTrade</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="welcome-message">
            <h2>Find Your Next Deal!</h2>
            <p>Search for items by name or filter by city to discover local listings.</p>
        </div>
        <div class="form-container">
            <h3>Search Listings</h3>
            <form action="search.php" method="GET">
                <label for="search">Item Name:</label>
                <input type="text" id="search" name="search" placeholder="Enter item name" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"><br><br>
                <label for="city">City (optional):</label>
                <input type="text" id="city" name="city" placeholder="Enter city" value="<?php echo isset($_GET['city']) ? htmlspecialchars($_GET['city']) : ''; ?>"><br><br>
                <input type="submit" value="Search">
            </form>
        </div>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && (isset($_GET['search']) || isset($_GET['city']))) {
            $search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
            $city = mysqli_real_escape_string($conn, $_GET['city'] ?? '');
            $sql = "SELECT p.id, p.name, p.price, p.image, u.name AS seller, u.city, u.phone 
                    FROM products p 
                    JOIN users u ON p.user_id = u.id 
                    WHERE p.name LIKE '%$search%'";
            if (!empty($city)) {
                $sql .= " AND u.city LIKE '%$city%'";
            }
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                echo "<div class='listings'>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='listing'>";
                    echo "<h4>" . htmlspecialchars($row['name']) . "</h4>";
                    echo "<p>Price: R" . number_format($row['price'], 2) . "</p>";
                    echo "<p>Seller: " . htmlspecialchars($row['seller']) . " (" . htmlspecialchars($row['city']) . ")</p>";
                    echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' width='100'>";
                    echo "<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_blank'>";
                    echo "<input type='hidden' name='cmd' value='_xclick'>";
                    echo "<input type='hidden' name='business' value='your_sandbox_email@example.com'>";
                    echo "<input type='hidden' name='item_name' value='" . htmlspecialchars($row['name']) . "'>";
                    echo "<input type='hidden' name='amount' value='" . $row['price'] . "'>";
                    echo "<input type='hidden' name='currency_code' value='ZAR'>";
                    echo "<input type='submit' value='Buy Now' class='paypal-btn'>";
                    echo "</form>";
                    echo "<a href='https://wa.me/" . htmlspecialchars($row['phone']) . "?text=Hi%20" . htmlspecialchars($row['seller']) . ",%20I’m%20interested%20in%20your%20" . htmlspecialchars($row['name']) . "' target='_blank' class='whatsapp-btn'>Contact Seller</a>";
                    echo "</div>";
                }
                echo "</div>";
                mysqli_free_result($result);
            } else {
                echo "<p>No listings found matching your search. Try a different name or city!</p>";
            }
        }
        mysqli_close($conn);
        ?>
        <p><a href="index.php">Back to Home</a></p>
    </main>
</body>
</html>