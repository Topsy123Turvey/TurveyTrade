
<?php
// Enable error logging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
file_put_contents('php_errors.log', '');
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
?>
<?php include 'header.php'; ?>
<main>
    <h2>Welcome to TurveyTrade!</h2>
    <p>Browse, buy, and sell items locally with ease. Sign up to start trading!</p>
</main>
</body>
</html>
