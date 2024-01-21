<?php
// Include your database connection code
require_once('mysqli.php');

// Initialize variables
$message = '';

// Check if the form is submitted for adding or updating a product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_product'])) {
        // Adding a new product
        $product_id = mysqli_real_escape_string($dbc, $_POST['product_id']);
        $product_name = mysqli_real_escape_string($dbc, $_POST['product_name']);
        $quantity = mysqli_real_escape_string($dbc, $_POST['quantity']);
        $price = mysqli_real_escape_string($dbc, $_POST['price']);
        $discount = mysqli_real_escape_string($dbc, $_POST['discount']);

        // Check if Product ID already exists
        $check_query = "SELECT * FROM products WHERE product_id='$product_id'";
        $check_result = mysqli_query($dbc, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $message = "Error adding product: Product ID already exists!";
        } else {
            // Calculate the price after discount
            $priceAfterDiscount = $price - ($price * $discount / 100);

            $insert_query = "INSERT INTO products (product_id, product_name, quantity, price, discount, price_after_discount) VALUES ('$product_id', '$product_name', '$quantity', '$price', '$discount', '$priceAfterDiscount')";
            $insert_result = mysqli_query($dbc, $insert_query);

            if ($insert_result) {
                $message = "Product added successfully!";
            } else {
                $message = "Error adding product: " . mysqli_error($dbc);
            }
        }
    } elseif (isset($_POST['update_product'])) {
        // Updating an existing product
        $product_id = mysqli_real_escape_string($dbc, $_POST['product_id']);
        $product_name = mysqli_real_escape_string($dbc, $_POST['product_name']);
        $quantity = mysqli_real_escape_string($dbc, $_POST['quantity']);
        $discount = mysqli_real_escape_string($dbc, $_POST['discount']);

        $update_query = "UPDATE products SET product_name='$product_name', quantity='$quantity', discount='$discount' WHERE product_id='$product_id'";
        $update_result = mysqli_query($dbc, $update_query);

        if ($update_result) {
            $message = "Product updated successfully!";
        } else {
            $message = "Error updating product: " . mysqli_error($dbc);
        }
    }
}

$page_title = 'Product Management';
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
            width: 80%; /* Adjust the width as needed */
            margin: auto;
        }

        .product-list,
        .form-container {
            width: 48%; /* Adjust the width as needed */
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

        .message {
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

<table>
    <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Actual Price</th>
        <th>Discount</th>
        <th>Price After Discount</th>
        <th>Restock</th>
        <th>Action</th>
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
        
        echo "<td><a href='restock.php'>Restock</a></td>";
        echo "<td><a href='?edit=" . $row['product_id'] . "'>Edit</a></td>";
        echo "</tr>";
    }
    ?>
</table>

<!-- Display the message -->
<?php if ($message) : ?>
    <div class="message">
        <?= $message ?>
    </div>
<?php endif; ?>

<!-- Form for adding/updating a product -->
<form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
    <?php
    // Check if editing an existing product
    if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
        $edit_product_id = mysqli_real_escape_string($dbc, $_GET['edit']);
        $edit_query = "SELECT * FROM products WHERE product_id='$edit_product_id'";
        $edit_result = mysqli_query($dbc, $edit_query);

        if ($edit_result && mysqli_num_rows($edit_result) > 0) {
            $edit_row = mysqli_fetch_assoc($edit_result);
            ?>
            <input type="hidden" name="product_id" value="<?= $edit_row['product_id'] ?>">
            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" value="<?= $edit_row['product_name'] ?>" required>

            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" value="<?= $edit_row['quantity'] ?>" required>

            <label for="discount">Discount:</label>
            <input type="number" name="discount" value="<?= isset($edit_row['discount']) ? $edit_row['discount'] : 0 ?>" required>

            <button type="submit" name="update_product">Update Product</button>
            <?php
        }
    } else {
        // Form for adding a new product
        ?>
        <label for="product_id">Product ID:</label>
        <input type="text" name="product_id" required>

        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" required>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" required>

        <label for="price">Price:</label>
        <input type="number" name="price" required>

        <label for="discount">Discount:</label>
        <input type="number" name="discount" value="0" required>

        <button type="submit" name="add_product">Add Product</button>
        <?php
    }
    ?>
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
