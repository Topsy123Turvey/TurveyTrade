
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
         $name = mysqli_real_escape_string($conn, $_POST['name']);
         $price = mysqli_real_escape_string($conn, $_POST['price']);
         $image = mysqli_real_escape_string($conn, $_POST['image']);
         $sql = "INSERT INTO products (user_id, name, price, image) VALUES ('$user_id', '$name', '$price', '$image')";
         if (mysqli_query($conn, $sql)) {
             header("Location: search.php");
             exit();
         } else {
             error_log("Add product failed: " . mysqli_error($conn));
             echo "Error adding product.";
         }
         mysqli_close($conn);
     }
     ?>
     <?php include 'header.php'; ?>
     <main>
         <h2>List an Item</h2>
         <form action="add_product.php" method="POST">
             <label for="user_id">Your User ID:</label>
             <input type="number" id="user_id" name="user_id" required><br><br>
             <label for="name">Item Name:</label>
             <input type="text" id="name" name="name" required><br><br>
             <label for="price">Price (R):</label>
             <input type="number" id="price" name="price" step="0.01" required><br><br>
             <label for="image">Image URL:</label>
             <input type="text" id="image" name="image"><br><br>
             <input type="submit" value="Add Listing">
         </form>
     </main>
     </body>
     </html>
     