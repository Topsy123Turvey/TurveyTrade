<?php
// Only start session if none is active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear session data
$_SESSION = [];
session_destroy();

// Prevent output before HTML
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out - TurveyTrade</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>TurveyTrade</h1>
    </header>
    <main>
        <div class="welcome-message">
            <h2>Goodbye for Now!</h2>
            <p>Thanks for trading with TurveyTrade! You’ve been logged out successfully. Hope to see you again soon!</p>
        </div>
        <div class="form-container">
            <a href="index.php" class="button">Return to Login</a>
        </div>
    </main>
</body>
</html>
<?php
ob_end_flush();
?>