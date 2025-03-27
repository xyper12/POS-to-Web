<?php

@include 'config.php';

if(isset($_POST['submit'])){
// database codes  here !! dire ka mag save/insert sa mga new accounts and also when logging in mag select ka sa account!!
   $name = mysqli_real_escape_string($con, $_POST['name']);
   $email = mysqli_real_escape_string($con, $_POST['email']);
   $password = md5($_POST['password']);
   $cpassword = md5($_POST['cpassword']);
   $user_type = $_POST['user_type'];

   $select = " SELECT * FROM accounts WHERE email = '$email' && password = '$password' ";

   $result = mysqli_query($con, $select);

   if(mysqli_num_rows($result) > 0){
// mo hatag og error message in ang isa ka account is already existing na !!
      $error[] = 'user already exist!';

   }else{
// error  message if ang password sa password field wala nag match !!
      if($password != $cpassword){
         $error[] = 'password not matched!';
      }else{
         // pag mag himo na og account or mag register dire sya mag insert!!
         $insert = "INSERT INTO accounts(name, email, password, user_type) VALUES('$name','$email','$password','$user_type')";
         mysqli_query($con, $insert);
         header('location:Login.php');
      }
   }

};


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register form</title>

   <!--  css  -->
   <link rel="stylesheet" href="Login.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post">
      <h3>register now</h3>
      <?php
      //this error msg is mag pop up whenever mag butang og invalid nga informations  !!
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
      <input type="text" name="name" required placeholder="enter your name">
      <input type="email" name="email" required placeholder="enter your email">
      <input type="password" name="password" required placeholder="enter your password">
      <input type="password" name="cpassword" required placeholder="confirm your password">
      <select name="user_type">
         <option value="user">user</option>
         <option value="admin">admin</option>
      </select>
      <input type="submit" name="submit" value="register now" class="form-btn">
      <p>already have an account? <a href="Login.php">login now</a></p>
   </form>

</div>

</body>
</html>