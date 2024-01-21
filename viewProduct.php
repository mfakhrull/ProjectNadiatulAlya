<?php
require_once('mysqli.php');
include('./includes/header.html');
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Product Details</title>
</head>

<body>

    <form action="viewProduct.php" method="post">
        <div class="container">
            <?php
            // Define an array of products
            $products = [
                ["Rehana", "img/Rehana.jpg"],
                ["Safura", "img/Safura.jpg"],
                ["Nailah", "img/Nailah.jpg"],
                ["Adeena", "img/Adeena.jpg"],
                ["Meryem", "img/Meryem.jpg"],
                ["Sabrina", "img/Sabrina.jpg"]
            ];

            // Function to generate product box
            function generateProductBox($productName, $imagePath)
            {
                echo '<div class="box">
                        <div class="images">
                            <div class="img-holder active">
                                <img src="' . $imagePath . '" alt="' . $productName . '" width="200" height="400">
                            </div>
                        </div>
                        <div class="basic-info">
                            <h1>' . $productName . '</h1>
                            <span>RM29</span>
                        </div>
                        <div class="description">
                            <p>Design special by Kekaboo Scarf Designer dan Big Boss</p>
                            <ul class="features">
                                <li><i class="fa-solid fa-circle-check"></i>Material New Improved Valentina Voile (Premium Cotton Voile)</li>
                                <li><i class="fa-solid fa-circle-check"></i>Bidang 45 Plus</li>
                                <li><i class="fa-solid fa-circle-check"></i>Kain sejuk & sangat selesa</li>
                                <li><i class="fa-solid fa-circle-check"></i>Design exclusive</li>
                            </ul>
                        </div>
                    </div>';
            }

            // Generate product boxes using the loop
            foreach ($products as $product) {
                generateProductBox($product[0], $product[1]);
            }

            
            ?>
        </div>

        <?php include('./includes/footer.html'); ?>
    </form>
</body>

</html>
