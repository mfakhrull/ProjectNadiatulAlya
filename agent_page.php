<?php
session_start();

// Ensure that the user is logged in and has the role of an Agent.
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Agent') {
    header("Location: index.php"); // Redirect to the login page if not logged in as an Agent.
    exit();
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

$page_title = 'Agent Dashboard'; // Adjusted the page title for clarity.
?>

<body>

<div id="wrapper">
    <div id="nav">
        <h1>Welcome, <?php echo $user_id; ?>!</h1>
        <ul>
            <li><a href="viewProduct.php">View Product Details</a></li>
            <li><a href="OrderForm.php">Make Order</a></li>
            <li><a href="Log Out.php">Log Out</a></li>
        </ul>
    </div>
</div>

</body>
</html>
