<?php
@include 'conn.php';

$isAdmin = true; 

if (!$isAdmin) {
   header("Location: login.php");
   exit;
}

$query = "SELECT * FROM `order`";
$result = mysqli_query($conn, $query);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_order'])) {
    $orderId = $_POST['order_id'];

    
    $deleteQuery = "DELETE FROM `order` WHERE id = ?";
    $stmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($stmt, 'i', $orderId);
    mysqli_stmt_execute($stmt);

   
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Failed to remove order.";
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
   

   <h1>Order List</h1>
<a href="Admin.php" class="btn" >HOME</a>
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
            <th>Status</th>
            <th>Action</th>
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
            echo "<td>" . ($row['order_status'] ? 'Returned' : 'Not Returned') . "</td>";
            echo "<td>
                    <form method='post' action='".$_SERVER['PHP_SELF']."' onsubmit='return confirmDelete()'>
                        <input type='hidden' name='order_id' value='" . $row['id'] . "'>
                        <button type='submit' name='remove_order'>Remove</button>
                    </form>
                  </td>";
            echo "</tr>";
         }
         ?>
      </tbody>
   </table>

   <script>
      function confirmDelete() {
         return confirm("Are you sure you want to delete this order?");
      }
   </script>
</body>
</html>
