<?php 
session_start(); // Start the session.

// If no session value is present, redirect the user.
if (!isset($_SESSION['user_id'])) {

	// Start defining the URL.
	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	// Check for a trailing slash.
	if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
		$url = substr ($url, 0, -1); // Chop off the slash.
	}
	$url .= '/login.php'; // Add the page.
	header("Location: $url");
	exit(); // Quit the script.
}

// Set the page title and include the HTML header.
$page_title = 'Logged In!';
include ('./includes/header.html');

// Print a customized message.
echo "<h1>Logged In!</h1>
<p>You are now logged in, {$_SESSION['user_id']}!</p>
<p><br /><br /></p>";

include ('./includes/footer.html');
?>