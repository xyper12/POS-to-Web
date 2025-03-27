<?php
@include 'conn.php';

// Fetch orders for the user side
$query = "SELECT * FROM `order`";
$result = mysqli_query($conn, $query);

// Handle marking orders as received
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mark_returned'])) {
    $orderId = $_POST['order_id'];

    // Update the order status to mark it as received
    $updateQuery = "UPDATE `order` SET order_status = 1 WHERE id = ?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, 'i', $orderId);
    mysqli_stmt_execute($stmt);

    // Check if the update was successful
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Redirect back to the same page to refresh the order list
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Failed to mark order as recturned.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Orders</title>

   <link rel="stylesheet" href="sale.css">
</head>
<body>
   <h1>My Orders</h1>
<a href="products.php" class="btn">Home</a>
   <table>
      <thead>
         <tr>
            <th>Name</th>
            <th>Number</th>
            <th>Email</th>
            <th>Method</th>
            <th>Barangay</th>
            <th>City</th>
            <th>Province</th>
            <th>Zip Code</th>
            <th>Price</th>
            <th>Event Date</th> 
            <th>Return Date</th> 
            <th>Status</th>
            <th>Action</th>
            <th>Penalty</th> 
         </tr>
      </thead>
      <tbody>
      <?php 
while ($row = mysqli_fetch_assoc($result)) {
   echo "<tr>";
   echo "<td>" . $row['name'] . "</td>";
   echo "<td>" . $row['number'] . "</td>";
   echo "<td>" . $row['email'] . "</td>";
   echo "<td>" . $row['method'] . "</td>";
   echo "<td>" . $row['barangay'] . "</td>";
   echo "<td>" . $row['city'] . "</td>";
   echo "<td>" . $row['province'] . "</td>";
   echo "<td>" . $row['zip_code'] . "</td>";
   echo "<td>" . $row['total_price'] . "</td>";
   echo "<td>" . $row['event_date'] . "</td>"; 
   echo "<td>" . $row['return_date'] . "</td>";

   // Calculate penalty if not returned
   $penalty = 0;
   if (!$row['order_status']) {
      $event_date = new DateTime($row['event_date']);
      $current_date = new DateTime();
      if ($current_date > $event_date) {
         $interval = $current_date->diff($event_date);
         $days_late = $interval->days;
         $penalty = $days_late * 500; // 500 pesos per day
      }
   }

   echo "<td>" . ($row['order_status'] ? 'Returned' : 'Not Returned') . "</td>";
   echo "<td>";
   if (!$row['order_status']) {
      echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
      echo "<input type='hidden' name='order_id' value='" . $row['id'] . "'>";
      echo "<button type='submit' name='mark_returned'>Mark Return</button>";
      echo "</form>";
   }
   echo "</td>";
   echo "<td>₱" . number_format($penalty) . "</td>"; // Display penalty
   echo "</tr>";
}
?>
      </tbody>
   </table>

</body>
</html>
