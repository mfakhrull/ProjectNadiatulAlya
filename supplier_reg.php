<?php 

$page_title = 'Register';
include ('./s_includes/header.html');

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

    require_once ('mysqli.php'); // Connect to the db.
        
    global $dbc;

    $errors = array(); // Initialize error array.

    // Check for a user id.
    if (empty($_POST['user_id'])) {
        $errors[] = 'You forgot to enter agent\'s id.';
    } else {
        $user_id = $_POST['user_id'];
    }
    
    // Check for a user name.
    if (empty($_POST['user_name'])) {
        $errors[] = 'You forgot to enter agent\'s name.';
    } else {
        $user_name = $_POST['user_name'];
    }
    
    // Check for a dob.
    if (empty($_POST['dob'])) {
        $errors[] = 'You forgot to enter your agent\'s dob.';
    } else {
        $dob = $_POST['dob'];
    }

    // Check for an address.
    if (empty($_POST['address'])) {
        $errors[] = 'You forgot to enter your agent\'s address.';
    } else {
        $address = $_POST['address'];
    }
    
    // Check for a phone_number.
    if (empty($_POST['phone_number'])) {
        $errors[] = 'You forgot to enter your agent\'s phone_number.';
    } else {
        $phone_number = $_POST['phone_number'];
    }

    // Check for an email address.
    if (empty($_POST['email'])) {
        $errors[] = 'You forgot to enter your agent\'s email address.';
    } else {
        $email = $_POST['email'];
    }
    
    // Check for a password and match against the confirmed password.
    if (!empty($_POST['password1'])) {
        if ($_POST['password1'] != $_POST['password2']) {
            $errors[] = 'Your password did not match the confirmed password.';
        } else {
            $password = $_POST['password1'];
        }
    } else {
        $errors[] = 'You forgot to enter a password.';
    }
    
    if (empty($errors)) { // If everything's okay.
    
        // Register the user in the database.
        
        // Check for previous registration.
        $query = "SELECT user_id FROM users WHERE email='$email'";
        $result = @mysqli_query ($dbc, $query); // Run the query.
        if (mysqli_num_rows($result) == 0) {

            // Make the query.
            $query = "INSERT INTO users (user_id, user_name, dob, address, phone_number, email, password, role, registration_date) VALUES ('$user_id', '$user_name', '$dob', '$address', '$phone_number', '$email', SHA('$password'), 'Agent', NOW() )";

    
            $result = @mysqli_query ($dbc, $query); // Run the query.
            if ($result) { // If it ran OK.
                
                // Print a message.
                echo '<h1 id="mainhead">Thank you!</h1>
                <p>Your agent has now been successfully registered. </p><p><br /></p>'; 
            
                // Include the footer and quit the script (to not show the form).
                include ('./includes/footer.html'); 
                exit();
                
            } else { // If it did not run OK.
                echo '<h1 id="mainhead">System Error</h1>
                <p class="error">You could not register your agent due to a system error. We apologize for any inconvenience.</p>'; // Public message.
                echo '<p>' . mysqli_error($dbc)  . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
                include ('./includes/footer.html'); 
                exit();
            }
                
        } else { // Already registered.
            echo '<h1 id="mainhead">Error!</h1>
            <p class="error">The email address has already been registered.</p>';
        }
        
    } else { // Report the errors.
    
        echo '<h1 id="mainhead">Error!</h1>
        <p class="error">The following error(s) occurred:<br />';
        foreach ($errors as $msg) { // Print each error.
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p><p><br /></p>';
        
    } // End of if (empty($errors)) IF.

    mysqli_close($dbc); // Close the database connection.
        
} // End of the main Submit conditional.
?>
<h2>Register</h2>
<form action="supplier_reg.php" method="post">
    <p>Agent's ID: <input type="text" name="user_id" size="15" maxlength="15" value="<?php if (isset($user_id)) echo $user_id; ?>" /></p>
    <p>Name: <input type="text" name="user_name" size="15" maxlength="15" value="<?php if (isset($user_name)) echo $user_name; ?>" /></p>
    <p>DOB: <input type="date" name="dob" size="15" maxlength="30" value="<?php if (isset($dob)) echo $dob; ?>" /></p>
    <p>Address: <input type="text" name="address" size="15" maxlength="30" value="<?php if (isset($address)) echo $address; ?>" /></p>
    <p>Phone Number: <input type="text" name="phone_number" size="15" maxlength="30" value="<?php if (isset($phone_number)) echo $phone_number; ?>" /></p>
    <p>Email Address: <input type="text" name="email" size="20" maxlength="40" value="<?php if (isset($email)) echo $email; ?>"  /> </p>
    <p>Password: <input type="password" name="password1" size="10" maxlength="20" /></p>
    <p>Confirm Password: <input type="password" name="password2" size="10" maxlength="20" /></p>
    <p><input type="submit" name="submit" value="Register" /></p>
    <input type="hidden" name="submitted" value="TRUE" />
</form>
<?php
include ('./s_includes/footer.html');
?>
