
     <?php
     // Enable error logging
     ini_set('display_errors', 1);
     ini_set('display_startup_errors', 1);
     error_reporting(E_ALL);
     file_put_contents('php_errors.log', '');
     ini_set('log_errors', 1);
     ini_set('error_log', 'php_errors.log');

     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         require 'db_connect.php';
         $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
         $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
         $seller_rating = mysqli_real_escape_string($conn, $_POST['seller_rating']);
         $product_rating = mysqli_real_escape_string($conn, $_POST['product_rating']);
         $comment = mysqli_real_escape_string($conn, $_POST['comment']);
         $sql = "INSERT INTO feedback (user_id, product_id, seller_rating, product_rating, comment) 
                 VALUES ('$user_id', '$product_id', '$seller_rating', '$product_rating', '$comment')";
         if (mysqli_query($conn, $sql)) {
             header("Location: feedback.php");
             exit();
         } else {
             error_log("Add feedback failed: " . mysqli_error($conn));
             echo "Error submitting feedback.";
         }
         mysqli_close($conn);
     }
     ?>
     <?php include 'header.php'; ?>
     <main>
         <h2>Submit Feedback</h2>
         <form action="feedback.php" method="POST">
             <label for="user_id">Your User ID:</label>
             <input type="number" id="user_id" name="user_id" required><br><br>
             <label for="product_id">Product ID:</label>
             <input type="number" id="product_id" name="product_id" required><br><br>
             <label for="seller_rating">Seller Rating (1-5):</label>
             <input type="number" id="seller_rating" name="seller_rating" min="1" max="5" required><br><br>
             <label for="product_rating">Product Rating (1-5):</label>
             <input type="number" id="product_rating" name="product_rating" min="1" max="5" required><br><br>
             <label for="comment">Comment:</label>
             <textarea id="comment" name="comment" rows="3"></textarea><br><br>
             <input type="submit" value="Submit Feedback">
         </form>
         <h2>Recent Feedback</h2>
         <div class="feedback">
             <?php
             require 'db_connect.php';
             $sql = "SELECT f.seller_rating, f.product_rating, f.comment, 
                            u.name AS buyer, p.name AS product
                     FROM feedback f
                     JOIN users u ON f.user_id = u.id
                     JOIN products p ON f.product_id = p.id";
             $result = mysqli_query($conn, $sql);

             if ($result && mysqli_num_rows($result) > 0) {
                 while ($row = mysqli_fetch_assoc($result)) {
                     echo "<div class='feedback-item'>";
                     echo "<p><strong>" . htmlspecialchars($row['buyer']) . "</strong> on " . htmlspecialchars($row['product']) . ":</p>";
                     echo "<p>Seller: " . $row['seller_rating'] . "/5</p>";
                     echo "<p>Product: " . $row['product_rating'] . "/5</p>";
                     echo "<p>Comment: " . htmlspecialchars($row['comment']) . "</p>";
                     echo "</div>";
                 }
                 mysqli_free_result($result);
             } else {
                 echo "<p>No feedback yet!</p>";
             }
             mysqli_close($conn);
             ?>
         </div>
     </main>
     </body>
     </html>
    