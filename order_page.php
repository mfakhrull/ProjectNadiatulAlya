<?php
session_start();

$page_title = 'Order Form';
include('./includes/header.html');

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

    require_once('mysqli.php'); // Connect to the db.

    global $dbc;

    $errors = array(); // Initialize error array.

    // Check for a name.
    if (empty($_POST['customer_name'])) {
        $errors[] = 'You forgot to enter customer name.';
    } else {
        $customer_name = $_POST['customer_name'];
    }

    // Check for an address.
    if (empty($_POST['address'])) {
        $errors[] = 'You forgot to enter your customer address.';
    } else {
        $address = $_POST['address'];
    }

    // Check for a contact number.
    if (empty($_POST['contact_number'])) {
        $errors[] = 'You forgot to enter your customer contact number.';
    } else {
        $contact_number = $_POST['contact_number'];
    }

    if (empty($errors)) { // If everything's okay.
        // Begin a transaction
        mysqli_begin_transaction($dbc);

        $all_orders_success = true;

        function getProductDetails($dbc, $product_name) {
            // Prepare the query to get the price and discount
            $query = "SELECT price, discount FROM products WHERE product_name = ?";
            $stmt = mysqli_prepare($dbc, $query);
        
            // Bind the parameter
            mysqli_stmt_bind_param($stmt, "s", $product_name);
        
            // Execute the query
            mysqli_stmt_execute($stmt);
        
            // Bind the result variables
            mysqli_stmt_bind_result($stmt, $price, $discount);
        
            // Fetch the result
            $result = mysqli_stmt_fetch($stmt);
        
            // Close the statement
            mysqli_stmt_close($stmt);
        
            // If the fetch was successful, return the price and discount
            if ($result) {
                return ['price' => $price, 'discount' => $discount];
            } else {
                return null; // Or handle the error as appropriate
            }
        }
        

        // Get the selected products and quantities
        foreach ($_POST['products'] as $product_name => $selected) {
            if ($selected == 1 && isset($_POST['quantity'][$product_name])) {
                $quantity = $_POST['quantity'][$product_name];
                $productDetails = getProductDetails($dbc, $product_name);
        
                if ($productDetails !== null) {
                    // Calculate the total sales amount for this product and quantity after discount
                    $discountedPrice = $productDetails['price'] - ($productDetails['price'] * $productDetails['discount'] / 100);
                    $total_sales = $discountedPrice * $quantity;

                    // Use prepared statement to insert the order into the database
                    $query = "INSERT INTO orders (customer_name, address, contact_number, product_name, quantity) VALUES (?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($dbc, $query);

                    // Bind parameters
                    mysqli_stmt_bind_param($stmt, "ssssi", $customer_name, $address, $contact_number, $product_name, $quantity);

                    // Execute the query
                    $result = mysqli_stmt_execute($stmt);

                    // Close the statement
                    mysqli_stmt_close($stmt);

                    // Now, insert the sales data into the sales table
                    $sales_query = "INSERT INTO sales (user_id, total_sales, units_sold) VALUES (?, ?, ?)";
                    $sales_stmt = mysqli_prepare($dbc, $sales_query);
                    mysqli_stmt_bind_param($sales_stmt, "sii", $_SESSION['user_id'], $total_sales, $quantity); // Assuming you have the agent's user_id stored in session
                    $sales_result = mysqli_stmt_execute($sales_stmt);
                    mysqli_stmt_close($sales_stmt);
                } else {
                    // Handle the case where price or discount is not found
                    $all_orders_success = false;
                    echo "Error: Product details not found for $product_name";
                    // Rollback transaction
                    mysqli_rollback($dbc);
                    break;
                }
            }
        }

        if ($all_orders_success) {
            // Commit transaction
            mysqli_commit($dbc);

            // If it ran OK, print a message
            echo '<h1 id="mainhead">Order submitted!</h1>
            <p>Your order needs approval by the Supplier first.</p><p><br /></p>';

            // Include the footer and quit the script (to not show the form).
            include('./includes/footer.html');
            exit();
        } else {
            // If not all orders were successful, rollback the transaction
            mysqli_rollback($dbc);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Form</title>
    <script>
        function toggleQuantityInput(checkbox, quantityInput) {
            quantityInput.disabled = !checkbox.checked;
            if (!checkbox.checked) {
                quantityInput.value = ""; // Reset quantity if checkbox is unchecked
            }
        }
    </script>
</head>


<body>

    <h3>Customer Information:</h3>

    <form action="order_page.php" method="post">

        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required>

        <br><label for="address">Customer Address:</label>
        <textarea id="address" name="address" required></textarea>

        <br><label for="contact_number">Contact Number:</label>
        <input type="tel" id="contact_number" name="contact_number" pattern="[0-9]{10}" required>

        <h3>Choose a Product and Enter Quantity:</h3>

        <?php
        $products = [
            "Kekaboo Rehana",
            "Kekaboo Safura",
            "Kekaboo Nailah",
            "Kekaboo Adeena",
            "Kekaboo Meryem",
            "Kekaboo Sabrina",
        ];

        foreach ($products as $product) {
            $id = str_replace(' ', '_', $product);
            echo "<label>";
            echo "<input type='checkbox' name='products[$product]' value='1' onchange='toggleQuantityInput(this, document.getElementById(\"quantity_$id\"))'>";
            echo "$product";
            echo "</label>";
            echo "<input type='number' id='quantity_$id' name='quantity[$product]' min='1' disabled required><br>";
        }
        ?>

        <button type="submit" name="submitted">Add Order</button>

    </form>

    <?php
    include('./includes/footer.html');
    ?>