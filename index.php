<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TurveyTrade</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <?php if (!isset($_SESSION['user_id'])) { ?>
            <div class="welcome-message">
                <h2>Welcome to TurveyTrade!</h2>
                <p>Your trusted C-2-C marketplace in South Africa. Join us to buy, sell, and trade with confidence.</p>
            </div>
            <?php if (isset($_SESSION['login_error'])) { ?>
                <p class="error-message"><?php echo htmlspecialchars($_SESSION['login_error']); ?></p>
                <?php unset($_SESSION['login_error']); ?>
            <?php } ?>
            <?php if (isset($_SESSION['signup_error'])) { ?>
                <p class="error-message"><?php echo htmlspecialchars($_SESSION['signup_error']); ?></p>
                <?php unset($_SESSION['signup_error']); ?>
            <?php } ?>
            <div class="form-container">
                <h3>Login</h3>
                <form action="login.php" method="POST">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required><br><br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required><br><br>
                    <div class="g-recaptcha" data-sitekey="6Lcfbl4rAAAAACjuLDJ8pXd-NclhJ_kl9LTVHfnb"></div><br>
                    <input type="submit" value="Login">
                </form>
                <p class="signup-prompt">Not signed up yet? <a href="#" onclick="showSignup()">Click here to sign up!</a></p>
                <div id="signup-form" style="display: none;">
                    <h3>Sign Up</h3>
                    <form action="signup.php" method="POST">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Enter your full name" required><br><br>
                        <label for="city">City:</label>
                        <input type="text" id="city" name="city" placeholder="Enter your city" required><br><br>
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required><br><br>
                        <label for="phone">Phone Number:</label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required><br><br>
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" placeholder="Choose a secure password" required><br><br>
                        <div class="g-recaptcha" data-sitekey="6Lcfbl4rAAAAACjuLDJ8pXd-NclhJ_kl9LTVHfnb"></div><br>
                        <input type="submit" value="Sign Up">
                    </form>
                </div>
            </div>
            <script>
                function showSignup() {
                    document.getElementById('signup-form').style.display = 'block';
                }
            </script>
        <?php } else { ?>
            <div class="welcome-message">
                <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
                <p>Your trusted C-2-C marketplace in South Africa. Buy, sell, and trade with confidence.</p>
                <?php if (isset($_SESSION['signup_success'])) { ?>
                    <p class="success-message"><?php echo htmlspecialchars($_SESSION['signup_success']); ?></p>
                    <?php unset($_SESSION['signup_success']); ?>
                <?php } ?>
                <?php if (isset($_SESSION['feedback_success'])) { ?>
                    <p class="success-message"><?php echo htmlspecialchars($_SESSION['feedback_success']); ?></p>
                    <?php unset($_SESSION['feedback_success']); ?>
                <?php } ?>
            </div>
        <?php } ?>
    </main>
</body>
</html>