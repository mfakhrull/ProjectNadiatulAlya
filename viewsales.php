<?php
$page_title = 'View Sales';
include('./s_includes/header.html');
echo '<h1 id="mainhead">Sales Performance by Agent</h1>';

require_once('mysqli.php'); // Connect to the db.
global $dbc;

// Make the query.
$query = "SELECT u.user_name, s.user_id, SUM(s.total_sales) as total_sales, SUM(s.units_sold) as total_units_sold
FROM sales AS s
JOIN users AS u ON s.user_id = u.user_id
WHERE u.role = 'Agent'
GROUP BY s.user_id";
$result = @mysqli_query($dbc, $query); // Run the query.

if ($result) {
    $num = mysqli_num_rows($result);

    if ($num > 0) {
        echo "<p>There are currently $num agents with sales.</p>\n";

        // Table header.
        echo '<table align="left" cellspacing="0" cellpadding="5" width="100%" border="1">';
        echo '<tr><th>Agent Name</th><th>Agent ID</th><th>Total Sales</th><th>Total Units Sold</th></tr>';

        // Variables to store total sales and total units sold.
        $totalSales = 0;
        $totalUnitsSold = 0;

        // Fetch and print all the records.
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo '<tr>';
            echo '<td align="left">' . $row['user_name'] . '</td>'; // Changed from 'agent_id' to 'user_name'
            echo '<td align="left">' . $row['user_id'] . '</td>';
            echo '<td align="left">' . $row['total_sales'] . '</td>';
            echo '<td align="left">' . $row['total_units_sold'] . '</td>'; // Changed from 'total_quantity' to 'total_units_sold'
            echo '</tr>';

            // Update total sales and total units sold.
            $totalSales += $row['total_sales'];
            $totalUnitsSold += $row['total_units_sold']; // Changed from 'total_quantity' to 'total_units_sold'
        }

        echo '</table>';

        // Display the total sales and total units sold.
        echo '<p>Total Sales: ' . $totalSales . '</p>';
        echo '<p>Total Units Sold: ' . $totalUnitsSold . '</p>';

        mysqli_free_result($result); // Free up the resources.
    } else {
        echo '<p class="error">There are currently no sales by agents.</p>';
    }
} else {
    // If there was an error in the query execution.
    echo '<p><font color="red">MySQL Error: ' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</font></p>';
}

// Include the footer.
include('./s_includes/footer.html');
?>
