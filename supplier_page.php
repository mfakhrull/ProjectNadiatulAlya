<?php
session_start();

// Ensure that the user is logged in and has the role of a Supplier.
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Supplier') {
    header("Location: s_index.php"); // Redirect to the login page if not logged in as a Supplier.
    exit();
}

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

$page_title = 'Supplier Dashboard'; // Adjusted the page title for clarity.
?>
<body>

<div id="wrapper">
    <div id="nav">
        <h1>Welcome to Tudung Store!</h1>
        <?php
        if (isset($_SESSION['user_id'])) {
            echo "<p>You are now logged in, {$_SESSION['user_id']}!</p>";
        }
        ?>
    </div>
</div>

</body>
</html>
