<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php include 'header.php'; ?>
<main>
    <h2>Welcome to TurveyTrade</h2>
    <p>Browse or list items for sale!</p>
</main>
</body>
</html>