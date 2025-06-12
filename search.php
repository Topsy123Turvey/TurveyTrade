<?php


session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
include 'db_connect.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Enable error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
file_put_contents('php_errors.log', '');
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

require 'db_connect.php';
?>
<?php include 'header.php'; ?>
<main>
    <h2>Search Listings</h2>
    <form action="search.php" method="GET">
        <label for="search">Search by Item Name:</label>
        <input type="text" id="search" name="search"><br><br>
        <label for="city">Filter by City:</label>
        <input type="text" id="city" name="city"><br><br>
        <input type="submit" value="Search">
    </form>
    <div class="listings">
        <?php
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $city = isset($_GET['city']) ? $_GET['city'] : '';
        
        $sql = "SELECT p.id, p.name, p.price, p.image, u.name AS seller, u.city, u.phone 
                FROM products p 
                JOIN users u ON p.user_id = u.id";
        
        $conditions = [];
        if ($search) {
            $conditions[] = "p.name LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
        }
        if ($city) {
            $conditions[] = "u.city LIKE '%" . mysqli_real_escape_string($conn, $city) . "%'";
        }
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='listing'>";
                echo "<h2>" . htmlspecialchars($row['name'] ?? '') . "</h2>";
                echo "<p>Price: R" . number_format($row['price'], 2) . "</p>";
                echo "<p>Seller: " . htmlspecialchars($row['seller'] ?? '') . " (" . htmlspecialchars($row['city'] ?? '') . ")</p>";
                if (!empty($row['image'])) {
                    echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name'] ?? '') . "' width='100'>";
                } else {
                    echo "<p>No image available</p>";
                }
                echo "<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_blank'>";
                echo "<input type='hidden' name='cmd' value='_xclick'>";
                echo "<input type='hidden' name='business' value='your_sandbox_email@example.com'>";
                echo "<input type='hidden' name='item_name' value='" . htmlspecialchars($row['name'] ?? '') . "'>";
                echo "<input type='hidden' name='amount' value='" . $row['price'] . "'>";
                echo "<input type='hidden' name='currency_code' value='ZAR'>";
                echo "<input type='submit' value='Buy Now' class='paypal-btn'>";
                echo "</form>";
                echo "<a href='https://wa.me/" . htmlspecialchars($row['phone'] ?? '') . "?text=Hi%20" . htmlspecialchars($row['seller'] ?? '') . ",%20I’m%20interested%20in%20your%20" . htmlspecialchars($row['name'] ?? '') . "' target='_blank' class='whatsapp-btn'>Contact Seller</a>";
                echo "</div>";
            }
            mysqli_free_result($result);
        } else {
            echo "No listings found!";
        }
        mysqli_close($conn);
        ?>
    </div>
</main>
</body>
</html>