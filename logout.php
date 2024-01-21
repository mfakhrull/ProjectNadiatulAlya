<?php
session_start();

// If no session is present, redirect the user.
if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

// Save the user_id for displaying a message
$user_id = $_SESSION['user_id'];

// Clear the session data.
session_unset();
session_destroy();

// Set the page title and include the HTML header.
$page_title = 'Logged Out!';

// Print a customized message.
echo "<h1>Logged Out!</h1>
<p>You are now logged out, {$user_id}!</p>
<p></p>";

//login link.
echo "<p>Click <a href='login.php'>here</a> to login.</p>";

include('./includes/footer.html');
?>
