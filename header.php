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
    <header>
        <h1>TurveyTrade</h1>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <nav class="nav-menu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="search.php">Search Listings</a></li>
                <li><a href="add_product.php">List an Item</a></li>
                <li><a href="signup.php">Sign Up</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="admin.php">Admin Dashboard</a></li>
            </ul>
        </nav>
        <script>
            document.querySelector('.hamburger').addEventListener('click', () => {
                document.querySelector('.nav-menu').classList.toggle('active');
            });
        </script>
    </header>
     