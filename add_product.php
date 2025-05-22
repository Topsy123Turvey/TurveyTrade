<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']); // Number, but escape anyway
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']); // Number, but escape for safety
    $image = mysqli_real_escape_string($conn, $_POST['image']);

    $sql = "INSERT INTO products (user_id, name, price, image) 
            VALUES ('$user_id', '$name', '$price', '$image')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>