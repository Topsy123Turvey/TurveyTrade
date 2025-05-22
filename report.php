<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $report_date = date('Y-m-d'); // Safe - no escape needed

    $sql = "INSERT INTO reports (product_id, report_date) 
            VALUES ('$product_id', '$report_date')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Report submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    
    mysqli_close($conn);
    header("Location: index.php");
    exit();
}
?>