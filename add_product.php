<?php
$page_title = 'Order Page';
include('./s_includes/header.html');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming the form sends an array for each product, adjust accordingly
    foreach ($_POST['products'] as $product_name => $quantity) {
        $price = $_POST['price'];

        $sql = "INSERT INTO product (product_name, quantity, price) VALUES ('$product_name', '$quantity', '$price')";

        if ($conn->query($sql) !== TRUE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

        $sql = "INSERT INTO orders (customer_name, address, contact_number, product_name, quantity, price) VALUES ('$customer_name', '$address', '$contact_number', '$product_name', '$quantity', '$price')";


    echo "Order placed successfully for $customer_name. We will contact you at $contact_number for further details.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Form</title>
</head>
<body>
    <h1>Order Form</h1>

<form method="post" action="order_page.php">
        <h3>Customer Information:</h3>

        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required>

        <br><label for="address">Customer Address:</label>
        <textarea id="address" name="address" required></textarea>

        <br><label for="postcode">Postcode:</label>
        <input type="text" id="postcode" name="postcode" required>

        <br><label for="city">City:</label>
        <input type="text" id="city" name="city" required>

        <br><label for="state">State:</label>
        <input type="text" id="state" name="state" required>

        <br><label for="contact_number">Contact Number:</label>
        <input type="tel" id="contact_number" name="contact_number" pattern="[0-9]{10}" required> 

    <form method="post" action="add_product.php">
        <h3>Choose a Product and Enter Quantity:</h3>

        <label><input type="checkbox" name="products[Kekaboo Rehana]" value="1"> Kekaboo Rehana</label>
        <input type="number" name="quantity[Kekaboo Rehana]" min="1" required><br>

        <label><input type="checkbox" name="products[Kekaboo Safura]" value="1"> Kekaboo Safura</label>
        <input type="number" name="quantity[Kekaboo Safura]" min="1" required><br>

         <label><input type="checkbox" name="products[Kekaboo Nailah]" value="1"> Kekaboo Nailah</label>
        <input type="number" name="quantity[Kekaboo Nailah]" min="1" required><br>

        <label><input type="checkbox" name="products[Kekaboo Wafiya]" value="1"> Kekaboo Wafiya</label>
        <input type="number" name="quantity[Kekaboo Wafiya]" min="1" required><br>

         <label><input type="checkbox" name="products[Kekaboo Adeena]" value="1"> Kekaboo Adeena</label>
        <input type="number" name="quantity[Kekaboo Adeena]" min="1" required><br>

        <label><input type="checkbox" name="products[Kekaboo Meryem]" value="1"> Kekaboo Meryem</label>
        <input type="number" name="quantity[Kekaboo Meryem]" min="1" required><br>

         <label><input type="checkbox" name="products[Kekaboo Suhayla]" value="1"> Kekaboo Suhayla</label>
        <input type="number" name="quantity[Kekaboo Suhayla]" min="1" required><br>

        <label><input type="checkbox" name="products[Kekaboo Salwa]" value="1"> Kekaboo Salwa</label>
        <input type="number" name="quantity[Kekaboo Salwa]" min="1" required><br>

         <label><input type="checkbox" name="products[Kekaboo Safaa]" value="1"> Kekaboo Safaa</label>
        <input type="number" name="quantity[Kekaboo Safaa]" min="1" required><br>

        <label><input type="checkbox" name="products[Kekaboo Sabrina]" value="1"> Kekaboo Sabrina</label>
        <input type="number" name="quantity[Kekaboo Sabrina]" min="1" required><br>

        <button type="submit">Add Stock</button>
    </form>


    <?php
include ('./s_includes/footer.html');
?>

</body>
</html>
