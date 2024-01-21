<?php
// Include your database connection code
require_once('mysqli.php');

// Initialize variables
$restock_message = '';

// Check if the form is submitted for adding or updating a product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['restock_product'])) {
        $product_id = mysqli_real_escape_string($dbc, $_POST['product_id']);
        $quantity = mysqli_real_escape_string($dbc, $_POST['quantity']);

        // Check if the product ID exists in the database
        $check_query = "SELECT * FROM products WHERE product_id = '$product_id'";
        $check_result = mysqli_query($dbc, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            // Product ID exists, proceed with restocking
            $update_query = "UPDATE products SET quantity=quantity+'$quantity' WHERE product_id='$product_id'";
            $update_result = mysqli_query($dbc, $update_query);

            if ($update_result) {
                $restock_message = "Product restocked successfully!";
            } else {
                $restock_message = "Error restocking product: " . mysqli_error($dbc);
            }
        } else {
            // Product ID does not exist
            $restock_message = "Error: Product with ID '$product_id' does not exist.";
        }
    }
}

$page_title = 'Stock Inventory Management';
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

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        .restock-message {
            color: green;
            margin-bottom: 10px;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<?php
include('./s_includes/header.html');
?>

<h2><?= $page_title ?></h2>

<!-- List Of Products Table -->
<table>
    <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Actual Price</th>
        <th>Discount</th>
        <th>Price After Discount</th>
    </tr>
    <?php
    // Query to get all products
    $query = "SELECT * FROM products";
    $result = mysqli_query($dbc, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['product_id'] . "</td>";
        echo "<td>" . $row['product_name'] . "</td>";
        echo "<td>" . $row['quantity'] . "</td>";
        echo "<td>" . $row['price'] . "</td>";
        echo "<td>" . $row['discount'] . "</td>";

        // Calculate the price after discount
        $priceAfterDiscount = $row['price'] - ($row['price'] * $row['discount'] / 100);

        echo "<td>" . $priceAfterDiscount . "</td>";
        echo "</tr>";
    }
    ?>
</table>

<!-- Display the restock message below the table -->
<?php if ($restock_message) : ?>
    <div class="<?= $update_result ? 'restock-message' : 'error-message' ?>">
        <?= $restock_message ?>
    </div>
<?php endif; ?>

<!-- Form for restocking a product -->
<form method="post" action="restock.php">
    <label for="product_id">Product ID:</label>
    <input type="text" name="product_id" required>

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" required>

    <button type="submit" name="restock_product">Restock Product</button>
</form>

<?php
// Close the database connection
mysqli_close($dbc);
?>

<?php
include('./s_includes/footer.html');
?>

</body>
</html>
