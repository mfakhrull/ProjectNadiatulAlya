<?php

session_start();

require_once('mysqli.php');

function getProductDetails($dbc, $product_name)
{
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['approve_order'])) {


        $order_id_to_approve = mysqli_real_escape_string($dbc, $_POST['approve_order']);

        $order_query = "SELECT * FROM orders WHERE order_id='$order_id_to_approve'";
        $order_result = mysqli_query($dbc, $order_query);

        if ($order_result && mysqli_num_rows($order_result) > 0) {
            // Fetch the order details
            $order_row = mysqli_fetch_assoc($order_result);

            // Get the product details again to calculate the discounted price
            $productDetails = getProductDetails($dbc, $order_row['product_name']);
            if ($productDetails !== null) {
                $discountedPrice = $productDetails['price'] - ($productDetails['price'] * $productDetails['discount'] / 100);
                $total_sales = $discountedPrice * $order_row['quantity'];

                // Update the order status to 'Approved'
                $update_order_status_query = "UPDATE orders SET order_status = 'Approved' WHERE order_id='$order_id_to_approve'";
                $update_order_status_result = mysqli_query($dbc, $update_order_status_query);

                if ($update_order_status_result) {
                    // Get the user_id from the order to use in the sales table
                    $user_id_from_order = $order_row['user_id']; // This is fetched from the orders table
                
                    // Insert the sales data into the sales table
                    $sales_query = "INSERT INTO sales (user_id, total_sales, units_sold) VALUES (?, ?, ?)";
                    $sales_stmt = mysqli_prepare($dbc, $sales_query);
                    mysqli_stmt_bind_param($sales_stmt, "sii", $user_id_from_order, $total_sales, $order_row['quantity']);
                    $sales_result = mysqli_stmt_execute($sales_stmt);
                    mysqli_stmt_close($sales_stmt);

                    // $sales_query = "INSERT INTO sales (user_id, total_sales, units_sold) VALUES (?, ?, ?)";
                    // $sales_stmt = mysqli_prepare($dbc, $sales_query);
                    // mysqli_stmt_bind_param($sales_stmt, "sii", $_SESSION['user_id'], $total_sales, $quantity); // Assuming you have the agent's user_id stored in session
                    // $sales_result = mysqli_stmt_execute($sales_stmt);
                    // mysqli_stmt_close($sales_stmt);

                    if ($sales_result) {
                        echo "Order approved and sales record created successfully!";
                    } else {
                        echo "Error inserting sales record: " . mysqli_error($dbc);
                    }
                } else {
                    echo "Error updating order status: " . mysqli_error($dbc);
                }
            } else {
                echo "Error: Product details not found for " . $order_row['product_name'];
            }
        }
    }
}

$page_title = 'Approval';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            display: flex;
            justify-content: space-between;
            width: 80%;
            margin: auto;
        }

        .product-list,
        .form-container {
            width: 48%;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }

        .form-container form {
            width: 100%;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: calc(100% - 16px);
            padding: 8px;
            margin-bottom: 12px;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>

<body>

    <?php
    include('./s_includes/header.html');
    ?>

    <h2><?= $page_title ?></h2>

    <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
        <h2>Orders</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Address</th>
                <th>Contact Number</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Order Date</th>
                <th>Pending</th>
            </tr>
            <?php
            // Query to get all products
            $query = "SELECT * FROM orders";
            $result = mysqli_query($dbc, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['order_id'] . "</td>";
                echo "<td>" . $row['customer_name'] . "</td>";
                echo "<td>" . $row['address'] . "</td>";
                echo "<td>" . $row['contact_number'] . "</td>";
                echo "<td>" . $row['product_name'] . "</td>";
                echo "<td>" . $row['quantity'] . "</td>";
                echo "<td>" . $row['order_date'] . "</td>";
                echo "<td>";

                if ($row['order_status'] == 'Pending') {
                    echo "<button type='submit' name='approve_order' value='" . $row['order_id'] . "'>Approve</button>";
                } else {
                    echo "Approved";
                }

                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </form>

    <?php
    echo '<h1 id="mainhead">Order Approved!</h1>';
    echo '<p>The order has been successfully approved by the Supplier.</p>';
    echo '<p>Thank you for your approval. The order is now confirmed.</p>';
    echo '<p><a href="products.php">Back to Product Management</a></p>';
    ?>

    <?php
    mysqli_close($dbc);
    ?>

    <?php
    include('./s_includes/footer.html');
    ?>

</body>

</html>