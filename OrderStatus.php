<?php
DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', ' ');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'projectip');

// Create a database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$customer_name = $_POST['customer_name'];
$product_name = $_POST['product_name'];
$quantity = $_POST['quantity'];
$total_price = $calculate_total_price_function($product_name, $quantity); // Implement this function

// Insert data into the database
$sql = "INSERT INTO orders (customer_name, product_name, quantity, total_price) VALUES ('$customer_name', '$product_name', $quantity, $total_price)";

if ($conn->query($sql) === TRUE) {
    echo "Order placed successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
