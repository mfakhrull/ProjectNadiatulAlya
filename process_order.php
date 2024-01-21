<?php
// Connect to the database (replace with your credentials)
$conn = mysqli_connect("localhost", "root", "", "projectip");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Process submitted order
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST["customer_name"];
    $address = $_POST["address"];
    $contact_number = $_POST["contact_number"];
    $product_name = $_POST["product_name"];
    $quantity = $_POST["quantity"];

    // Use prepared statements to prevent SQL injection
    $sql = "INSERT INTO orders (customer_name, address, contact_number, product_name, quantity) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);
    
    // Bind parameters to the statement
    mysqli_stmt_bind_param($stmt, "sssss", $customer_name, $address, $contact_number, $product_name, $quantity);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Check for errors
    if (mysqli_stmt_errno($stmt) !== 0) {
        echo "Error: " . mysqli_stmt_error($stmt);
    } else {
        echo "Order submitted successfully!";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Close the connection
mysqli_close($conn);
?>
