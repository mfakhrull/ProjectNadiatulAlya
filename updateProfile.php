<?php
// Include your database connection code
require_once('mysqli.php');

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or handle unauthorized access
    header("Location: login.php");
    exit();
}

// Check if the form is submitted for adding or updating a product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // (Your existing code for adding and updating products)

    if (isset($_POST['update_profile'])) {
        $user_id = $_SESSION['user_id'];
        $email = mysqli_real_escape_string($dbc, $_POST['email']);
        $address = mysqli_real_escape_string($dbc, $_POST['address']);
        $phone_number = mysqli_real_escape_string($dbc, $_POST['phone_number']);

        $updateQuery = "UPDATE users SET email=?, address=?, phone_number=? WHERE user_id=?";
        $updateResult = mysqli_query($dbc, $updateQuery);

        if ($updateResult) {
            echo "Profile updated successfully!";
            // You can redirect or perform other actions after a successful update
        } else {
            echo "Error updating profile: " . mysqli_error($dbc);
        }
    }
}

$page_title = 'Profile Update';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?></title>
    <style>
        /* Your CSS styles here */
    </style>
</head>
<body>

    <?php
    include('./s_includes/header.html');
    ?>

    <h2><?= $page_title ?></h2>
    <table>
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>DOB</th>
            <th>Email</th>
            <th>Address</th>
            <th>Phone Number</th>
            <th>Edit</th>
        </tr>
        <?php
        // Query to get the logged-in user's profile
        $user_id = $_SESSION['user_id'];
        $query = "SELECT * FROM users WHERE user_id='$user_id'";
        $result = mysqli_query($dbc, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['user_id'] . "</td>";
            echo "<td>" . $row['user_name'] . "</td>";
            echo "<td>" . $row['dob'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['address'] . "</td>";
            echo "<td>" . $row['phone_number'] . "</td>";
            echo "<td><a href='" . $_SERVER["PHP_SELF"] . "?edit=" . $row['user_id'] . "'>Edit</a></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <!-- Form for update profile -->
    <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
        <?php
        // Check if editing the logged-in user's profile
        if (isset($_GET['edit']) && is_numeric($_GET['edit']) && $_GET['edit'] == $_SESSION['user_id']) {
    $edit_user_id = $_SESSION['user_id'];
    $edit_query = "SELECT * FROM users WHERE user_id='$edit_user_id'";
    $edit_result = mysqli_query($dbc, $edit_query);

    if ($edit_result && mysqli_num_rows($edit_result) > 0) {
        $edit_row = mysqli_fetch_assoc($edit_result);
                ?>
                <input type="hidden" name="user_id" value="<?= $edit_row['user_id'] ?>">
                <label for="email">Email:</label>
                <input type="text" name="email" value="<?= $edit_row['email'] ?>" required>

                <label for="address">Address:</label>
                <input type="text" name="address" value="<?= $edit_row['address'] ?>" required>

                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" value="<?= $edit_row['phone_number'] ?>" required>

                <button type="submit" name="update_profile">Update</button>
                <?php
            }
        } else {
            // Display a message or handle unauthorized access
            echo "You are not authorized to edit this profile.";
        }
        ?>
    </form>

    <?php
    // Close the database connection
    mysqli_close($dbc);
    ?>

    <?php
    include('./s_includes/footer.html');
    ?>

</body>
</html>
