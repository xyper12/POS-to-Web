<header class="header">

   <div class="flex">

      <a href="Admin.php" class="logo">Gown Rental </a>

      <nav class="navbar">
     
     
       <a href="myorder.php"> Orders</a>
         
        
         <a href="products.php">view gowns</a>
       
         <a href="Login.php"> Logout</a>
      </nav>

      <?php
      
      $select_rows = mysqli_query($conn, "SELECT * FROM cart") or die("query failed");
      $row_count = mysqli_num_rows($select_rows);

      ?>

      <a href="cart.php" class="cart">cart <span><?php echo $row_count; ?></span> </a>

      <div id="menu-btn" class="fas fa-bars"></div>

   </div>

</header>