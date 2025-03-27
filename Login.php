<?php

@include 'config.php';

session_start();

if(isset($_POST['submit'])){
// databse codes  dire na part ang mag save and also read if ang isa ka account nag exist!

   $name = mysqli_real_escape_string($con, $_POST['name'] ?? '');
   $email = mysqli_real_escape_string($con, $_POST['email'] ?? '');
   $password = md5($_POST['password'] ?? '');
   $cpassword = md5($_POST['cpassword'] ?? '');
   $user_type = $_POST['user_type'] ?? '';
   

   $select = " SELECT * FROM accounts WHERE email = '$email' && password = '$password' ";

   $result = mysqli_query($con, $select);

   if(mysqli_num_rows($result) > 0){

      $row = mysqli_fetch_array($result);
// this codes read/identify if ang account is user or admin!
      if($row['user_type'] == 'admin'){

         $_SESSION['admin_name'] = $row['name'];
         header('location:Admin.php');
       
        

      }elseif($row['user_type'] == 'user'){

         $_SESSION['user_name'] = $row['name'];
         header('location:products.php');

      }
     
   }else{
      $error[] = 'incorrect email or password!';
   }

};
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Gown Rental Management</title>

  
   <link rel="stylesheet" href="Login.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post">
      <h3>Gown Rental Management</h3>
      <?php
      // error message if the username is invalid and the password !!
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
      <input type="email" name="email" required placeholder="enter your email">
      <input type="password" name="password" required placeholder="enter your password">
      <input type="submit" name="submit" value="login now" class="form-btn">
      <p>don't have an account? <a href="Registration.php">register now</a></p>
   </form>

</div>

</body>
</html>


