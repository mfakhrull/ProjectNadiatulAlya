<?php 
session_start();

// Check if the user is already logged in, redirect to the appropriate page
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'Supplier') {
        header("Location: index.php");
    } elseif ($_SESSION['role'] == 'Agent') {
        header("Location: s_index.php");
    }
    exit();
}

// Check if the form has been submitted.
if (isset($_POST['submit'])) {

    require_once('mysqli.php'); // Connect to the db.
    global $dbc;

    $errors = array(); // Initialize error array.

    // Check for a user id.
    if (empty($_POST['user_id'])) {
        $errors[] = 'You forgot to enter your user id.';
    } else {
        $user_id = ($_POST['user_id']);
    }

    // Check for an email address.
    if (empty($_POST['email'])) {
        $errors[] = 'You forgot to enter your email address.';
    } else {
        $email = $_POST['email'];
    }

    // Check for a password.
    if (empty($_POST['password'])) {
        $errors[] = 'You forgot to enter your password.';
    } else {
        $password = $_POST['password'];
    }

    if (empty($errors)) { // If everything's OK.

        // Authenticate the user.
        $query = "SELECT user_id, role FROM users WHERE user_id='$user_id' AND password=SHA('$password')";
        $result = @mysqli_query($dbc, $query); // Run the query.

        if (!$result) {
            die('Query failed: ' . mysqli_error($dbc)); // Display MySQL error if the query fails.
}

        $row = mysqli_fetch_assoc($result);

        if ($row) { // A record was pulled from the database.
            // Set session variables.
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role'] = $row['role'];
        
            // Store additional agent information in the session.
            $_SESSION['agent_info'] = array(
                'agent_id' => $row['user_id'],  // Assuming agent_id is the user_id in your database
                'email' => $row['email'],       // Replace with the actual column name
                // Add more agent information as needed
            );
        
            // Redirect based on user role.
            if ($row['role'] == 'Supplier') {
                // Redirect to Supplier page.
                header("Location: s_index.php");
                exit();
            } elseif ($row['role'] == 'Agent') {
                // Redirect to Agent page.
                header("Location: index.php");
                exit();
            }
        } else {
            $errors[] = 'Invalid login credentials.';
        }

    } // End of if (empty($errors)) IF.

    mysqli_close($dbc); // Close the database connection.

} else { // Form has not been submitted.

    $errors = NULL;
} // End of the main Submit conditional.

// Begin the page now.
$page_title = 'Login';

if (!empty($errors)) { // Print any error messages.
    echo '<h1 id="mainhead">Error!</h1>
    <p class="error">The following error(s) occurred:<br />';
    foreach ($errors as $msg) { // Print each error.
        echo " - $msg<br />\n";
    }
    echo '</p><p>Please try again.</p>';
}

// Create the form.
?>
<h2>Login</h2>
<form action="" method="post">
    <p>User ID: <input type="text" name="user_id" size="20" maxlength="40" /> </p>
    <p>Email Address: <input type="text" name="email" size="20" maxlength="40" /></p>
    <p>Password: <input type="password" name="password" size="20" maxlength="20" /></p>
    <p><input type="submit" name="submit" value="Login" class="btnLogin" /></p>
</form>
<?php
?>
