<?php
$page_title = 'Products';
include ('./includes/header.html');

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

    require_once ('mysqli.php'); // Connect to the db.

    global $dbc;
    
    $errors = array(); // Initialize error array.

    $query = "SELECT * FROM products";

    // Run the query and handle the results.
    if ($result = $conn->query($query)) {
        if ($result->num_rows > 0) { // Some records returned.
            // Print each record in a loop.
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>{$row['name']} - \${$row['price']} - Quantity: {$row['quantity']}</li>";
            }
            echo "</ul>";
        } else { // No records returned.
            echo '<p>No products available.</p>';
        }
    } else { // Query didn't run properly.
        echo '<p><font color="red">MySQL Error: ' . $conn->error . '<br /><br />Query: ' . $query . '</font></p>';// Debugging message.
    }

    // Free the result and close the connection.
    $result->free();
    $conn->close();
} else {
    echo '<p>The form has not been submitted.</p>';
}
?>
