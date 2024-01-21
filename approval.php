<?php
// Connect to the database (replace with your credentials)
$conn = mysqli_connect("localhost", "root", "nbuser", "projectip1");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve pending orders
$sql = "SELECT * FROM orders WHERE order_status = 'pending'";
$result = mysqli_query($conn, $sql);

echo "<h1>Order Approval Dashboard</h1>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Customer Name</th><th>Product Name</th><th>Quantity</th><th>Action</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['customer_name']}</td>";
    echo "<td>{$row['product']}</td>";
    echo "<td>{$row['quantity']}</td>";
    echo "<td><a href='approve_order.php?id={$row['id']}'>Approve</a> | <a href='reject_order.php?id={$row['id']}'>Reject</a></td>";
    echo "</tr>";
}

echo "</table>";

mysqli_close($conn);
?>
