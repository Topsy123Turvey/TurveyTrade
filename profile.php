
     <?php
     
     
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit();
        }
        include 'db_connect.php';

     
     // Enable error logging
     ini_set('display_errors', 1);
     ini_set('display_startup_errors', 1);
     error_reporting(E_ALL);
     file_put_contents('php_errors.log', '');
     ini_set('log_errors', 1);
     ini_set('error_log', 'php_errors.log');

     require 'db_connect.php';
     $user = null;
     if (isset($_GET['user_id'])) {
         $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
         $sql = "SELECT name, city, email FROM users WHERE id = '$user_id'";
         $result = mysqli_query($conn, $sql);
         if ($result && mysqli_num_rows($result) > 0) {
             $user = mysqli_fetch_assoc($result);
         }
         mysqli_free_result($result);
         mysqli_close($conn);
     }
     ?>
     <?php include 'header.php'; ?>
     <main>
         <h2>Your Profile</h2>
         <form action="profile.php" method="GET">
             <label for="user_id">Enter Your User ID:</label>
             <input type="number" id="user_id" name="user_id" required><br><br>
             <input type="submit" value="View Profile">
         </form>
         <?php if ($user): ?>
             <h3>Profile Details</h3>
             <p>Name: <?php echo htmlspecialchars($user['name']); ?></p>
             <p>City: <?php echo htmlspecialchars($user['city']); ?></p>
             <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
         <?php elseif (isset($_GET['user_id'])): ?>
             <p>No user found.</p>
         <?php endif; ?>
     </main>
     </body>
     </html>
     