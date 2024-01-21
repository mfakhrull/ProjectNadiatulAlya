<?php

$page_title = 'Supplier Dashboard';
include('./includes/header.html');

require_once('mysqli.php'); // Connect to the db.

global $dbc;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['login'])) {
        // Login process
        $errors = array();

        // Validate input for login
        $supplier_email = mysqli_real_escape_string($dbc, trim($_POST['email']));
        $supplier_password = mysqli_real_escape_string($dbc, trim($_POST['password']));
        $hashed_password = SHA1($supplier_password);

        if (empty($errors)) {
    echo "Email: $supplier_email, Hashed Password: $hashed_password";  // Debug statement

    // Check for previous registration.
    $query = "SELECT user_id, supplier_name FROM suppliers WHERE supplier_email='$supplier_email' AND supplier_password='$hashed_password'";
    echo "Query: $query";  // Debug statement

    $result = @mysqli_query($dbc, $query);

            if ($result) {
                if (mysqli_num_rows($result) == 1) {
                    // Login successful
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    ?>
                    <h1 id="mainhead">Welcome, <?php echo $row['supplier_name']; ?>!</h1>
                    <p>You are now logged in.</p>
                    <p>Choose an action:</p>
                    <ul>
                        <li><a href="update_profile.php">Update Profile</a></li>
                        <li><a href="register_agent.php">Register New Agent</a></li>
                    </ul>
                    <?php
                } else {
                    echo '<h1 id="mainhead">Error!</h1>
                            <p class="error">Invalid email or password. Please try again.</p>';
                }
            } else {
                // Handle the case where the query did not run successfully.
                echo '<h1 id="mainhead">System Error</h1>
                    <p class="error">There was an issue with the database query.</p>'; // Public message.
                echo '<p>' . mysqli_error($dbc)  . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
                include ('./includes/footer.html');
                exit();
            }
        }
    }
}

include('./includes/footer.html');
?>
