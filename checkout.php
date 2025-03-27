<?php

@include 'conn.php';

if(isset($_POST['order_btn'])){

   $name = $_POST['name'];
   $number = $_POST['number'];
   $email = $_POST['email'];
   $method = $_POST['method'];
   $barangay = $_POST['barangay'] ;
   $city = $_POST['city'] ;
   $province = $_POST['province'] ;
   $zip_code = $_POST['zip_code'];
   $event_date = $_POST['event_date'];
   $return_date = $_POST['return_date'];
   $uploaded_img = '../POS to WEB/uploaded_img/';
   $user_image = $_FILES['uploaded_img']['name'] ?? '' ;

   if ($user_image != '') {
       $image_path = 'uploaded_img/' . $user_image;
       move_uploaded_file($_FILES['uploaded_img'], $image_path);
       $user_image = $image_path;
   }
   if (isset($cart_query) && isset($detail_query)) {
      // Add the uploaded_image variable in the following line
     $detail_query = mysqli_query($conn, "INSERT INTO `order`(name, number, email, method, barangay, city, province, zip_code, uploaded_img, total_products, total_price, event_date, return_date) VALUES('$name','$number','$email','$method','$barangay','$city','$province','$zip_code','$uploaded_img','$total_product','$price_total', '$event_date', '$return_date')") or die('query failed');

      
  }
  
  $cart_query = mysqli_query($conn, "SELECT * FROM `cart`");
   $price_total = 0;
   if(mysqli_num_rows($cart_query) > 0){
      while($product_item = mysqli_fetch_assoc($cart_query)){
         $product_name[] = $product_item['product_name'] .' ('. $product_item['quantity'] .') ';
         $product_price = number_format($product_item['price'] * $product_item['quantity']);
         $price_total += $product_price;
      };
   };

   $total_product = implode(', ',$product_name);
   $detail_query = mysqli_query($conn, "INSERT INTO `order`(name, number, email, method, barangay, city, province, zip_code, uploaded_img, total_products, total_price, event_date, return_date) VALUES('$name','$number','$email','$method','$barangay','$city','$province','$zip_code','$uploaded_img','$total_product','$price_total', '$event_date', '$return_date')") or die('query failed');

   if($cart_query && $detail_query){
      echo "
      <div class='order-message-container'>
      <div class='message-container'>
         <h3>thank you for Renting!</h3>
         <div class='order-detail'>
            <span>".$total_product."</span>
            <span class='total'> total : ₱".$price_total."/-  </span>
         </div>
         <div class='customer-details'>
            <p> your name : <span>".$name."</span> </p>
            <p> your number : <span>".$number."</span> </p>
            <p> your email : <span>".$email."</span> </p>
            <p> your address : <span>".$barangay.",  ".$city.", ".$province.",   ".$zip_code."</span> </p>
            <p> your payment mode : <span>".$method."</span> </p>
            <p> event date : <span>".$event_date."</span> </p> 
                 <p> return date : <span>".$return_date."</span> </p> 
         </div>
            <a href='products.php' class='btn'>Place Order</a>
            <a href='checkout.php' class='btn'>Cancel</a>
         </div>
      </div>
      ";
   }

}
if($cart_query && $detail_query){
             
   mysqli_query($conn, "DELETE FROM `cart`");

   echo "
    <div class='order-message-container'>
   <div class='message-container'>
      <h3>thank you for shopping!</h3>
      <div class='order-detail'>
         <span>".$total_product."</span>
         <span class='total'> total : ₱".$price_total."/-  </span>
      </div>
      <div class='customer-details'>
         <p> your name : <span>".$name."</span> </p>
         <p> your number : <span>".$number."</span> </p>
         <p> your email : <span>".$email."</span> </p>
         <p> your address : <span>".$barangay.",  ".$city.", ".$province.",   ".$zip_code."</span> </p>
         <p> your payment mode : <span>".$method."</span> </p>
         <p> event date : <span>".$event_date."</span> </p> 
         <p> return date : <span>".$return_date."</span> </p> 
         <p> Please return on time, a penalty of 500 pesos per day if not return in time </p>
      </div>
         <a href='products.php' class='btn'>Place Order</a>
         <a href='checkout.php' class='btn'>Cancel</a>
         <button class='btn' onclick='printOrder()'>Print</button> <!-- Print button -->
      </div>
   </div>
   <script>
      function printOrder() {
         window.print(); // Trigger the print dialog
      }
   </script>
   ";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="admin.css">

</head>
<body>

<?php include 'header.php'; ?>

<div class="container">

<section class="checkout-form">

   <h1 class="heading">complete your order</h1>

   <form action="" method="post">

   <div class="display-order">
      <?php
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart`");
         $total = 0;
         $grand_total = 0;
         if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = number_format($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total = $total += $total_price;

        
      ?>
      <span><?= $fetch_cart['product_name']; ?>(<?= $fetch_cart['quantity']; ?>)</span>
      <?php
         }
      }else{
         echo "<div class='display-order'><span>your cart is empty!</span></div>";
      }
      ?>
      <span class="grand-total"> grand total : ₱<?= $grand_total; ?> </span>
   </div>

      <div class="flex">
         <div class="inputBox">
            <span>your name</span>
            <input type="text" placeholder="enter your name" name="name" required>
         </div>
         <div class="inputBox">
            <span>your number</span>
            <input type="number" placeholder="enter your number" name="number" required>
         </div>
         <div class="inputBox">
            <span>your email</span>
            <input type="email" placeholder="enter your email" name="email" required>
         </div>
         <div class="inputBox">
            <span>Payment Method</span>
            <select name="method">
               <option value="cash on delivery" selected>Cash On Devlivery</option>
               <option value="credit cart">credit cart</option>
               <option value="paypal">Paypal</option>
               <option value="paypal">Gcash</option>
            </select>
         </div>
         <div class="inputBox">
            <span>barangay </span>
            <input type="text" placeholder="e.g. Bry.San marino, Juan dela cruz street." name="barangay" required>
         </div>
     
         <div class="inputBox">
            <span>city</span>
            <input type="text" placeholder="e.g. Digos City" name="city" required>
         </div>
         <div class="inputBox">
            <span>province</span>
            <input type="text" placeholder="e.g. Davao Del Sur" name="province" required>
         </div>
   
         <div class="inputBox">
            <span>zip code</span>
            <input type="text" placeholder="e.g. 123456" name="zip_code" required>
         </div>
         <div class="inputBox">
    <span>Date of Event</span>
    <input type="date" name="event_date" required>
</div>
<div class="inputBox">
    <span>Return Date</span>
    <input type="date" name="return_date" required>
</div>
         <div class="inputBox">
            <span>Proof Of Payment</span>
            <input type="file" name="uploaded_image" id="image-input" accept="image/*">
<label for="image-input" class="custom-file-upload">
  <i class="fas fa-camera fa-2x"></i>
  Upload Your Image
</label>
         </div>
      </div>
      <input type="submit" value="order now" name="order_btn" class="btn">
   </form>

</section>

</div>


<!-- custom js file link  -->
<script src="jscript.js"></script>
   
</body>
</html>