<?php
// Connect to the database (replace with your credentials)
$conn = mysqli_connect("localhost", "root", "", "projectip");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Approve the order
$order_id = $_GET['id'];

// Use a prepared statement to prevent SQL injection
$sql = "UPDATE orders SET status = 'approved' WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

// Bind the order_id parameter to the statement
mysqli_stmt_bind_param($stmt, "i", $order_id);

// Execute the statement
mysqli_stmt_execute($stmt);

// Check for errors
if (mysqli_stmt_errno($stmt) !== 0) {
    echo "Error: " . mysqli_stmt_error($stmt);
} else {
    echo "Order approved successfully!";
}

// Close the statement
mysqli_stmt_close($stmt);

mysqli_close($conn);
?>
