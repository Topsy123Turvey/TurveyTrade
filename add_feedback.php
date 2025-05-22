<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $seller_rating = mysqli_real_escape_string($conn, $_POST['seller_rating']);
    $product_rating = mysqli_real_escape_string($conn, $_POST['product_rating']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment'] ?: 'No comment');

    $sql = "INSERT INTO feedback (user_id, product_id, seller_rating, product_rating, comment) 
            VALUES ('$user_id', '$product_id', '$seller_rating', '$product_rating', '$comment')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Feedback submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    
    mysqli_close($conn);
    header("Location: index.php");
    exit();
}
?>