<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Stock</title>

    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 300px;
        margin: 100px auto;
        background-color: #fff;
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        color: #333;
    }

    form {
        text-align: left;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin: 8px 0;
        box-sizing: border-box;
    }
    
    .error-message {
        color: #d9534f;
        background-color: #f2dede;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ebccd1;
        border-radius: 4px;
    }

    .container form {
        margin-top: 20px;
    }

    </style>
</head>
<body>

<?php # Script 7.7 - register.php (3rd version after Scripts 7.3 & 7.5)

$page_title = 'Update Stock';
include ('./includes/header.html');

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	require_once ('mysqli.php'); // Connect to the db.
		
	global $dbc;
	

	$errors = array(); // Initialize error array.
	
	// Check for a  product id.
	if (empty($_POST['id'])) {
		$errors[] = 'You forgot to enter the product id.';
	} else {
		$id = $_POST['id'];
	}
	
	
    // Check for a stock.
	if (empty($_POST['stock'])) {
		$errors[] = 'You forgot to enter product stock.';
	} else {
		$s = $_POST['stock'];
	}
	
	if (empty($errors)) { // If everything's okay.

            // Check if the product with the given ID already exists.
            $check_query = "SELECT id FROM product WHERE id='$id'";
            $check_result = @mysqli_query($dbc, $check_query);

            if (mysqli_num_rows($check_result) == 0) {
                echo '<h1 id="mainhead">Error!</h1>
                      <p class="error">Product with ID ' . $id . ' does not exist. Cannot update stock.</p>';
            } else {

			// Make the UPDATE query.
			$query = "UPDATE product SET stock='$s' WHERE id='$id'";		
            $result = @mysqli_query($dbc, $query); // Run the query.
            
            if ($result) { // If it ran OK.
                
				// Print a message.
				echo '<h1 id="mainhead">Thank you!</h1>
				<p>Successfully updated stock. </p><p><br /></p>';	
			
				// Include the footer and quit the script (to not show the form).
				include ('./includes/footer.html'); 
				exit();
				
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The product stock could not be updated due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				include ('./includes/footer.html'); 
				exit();
			}
        }
			// Include the footer and quit the script (to not show the form).
            include ('./includes/footer.html');
            exit();			
            
	} else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	} // End of if (empty($errors)) IF.

	mysqli_close($dbc); // Close the database connection.
		
}// End of the main Submit conditional
?>
<h2><center>Update Stock</center></h2>
<form action="updatestock.php" method="post">
	<p>ID: <input type="text" name="id" size="15" maxlength="15" value="<?php if (isset($_POST['id'])) echo $_POST['id']; ?>" /></p>
	<p>Stock: <input type="text" name="stock" size="15" maxlength="30" value="<?php if (isset($_POST['stock'])) echo $_POST['stock']; ?>" /></p>
	<p><input type="submit" name="submit" value="Update" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
</form>
<?php
include ('./includes/footer.html');
?>