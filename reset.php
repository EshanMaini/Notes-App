<?php
session_start();
include_once('includes/connection.php');

if(isset($_POST['pass']) && isset($_POST['cpass'])){
  $pass = $_POST['pass'];
  $cpass = $_POST['cpass'];

  if(!empty($pass) && !empty($cpass)){
    if($pass == $cpass){
      $query = $pdo->prepare("update users set user_password = ? where user_id = ?");
      $query->bindValue(1, $pass);
      $query->bindValue(2, $_SESSION['userid']);
      $query->execute();
      $error =  "Password Changed Successfully!";
    }else{
      $error =  "password does not match";
    }

  }else{
    $error =  "all fields are required";
  }
}


 ?>
 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <title>Change Password - My Notes App</title>
     <link rel="stylesheet" href="css/main.css" media="screen" title="no title" charset="utf-8">
   </head>
   <body>
     <div class="container">
       <header class="logo">
         <a href="index.php"> My Notes App </a>
       </header>

       <div class="form-cont">
         <h4 class= "error"><?php if(isset($error)){echo $error;} ?></h4>
         <form class="login" action="reset.php" method="post" style="width:100%; border:none;">
           <input type="password" name="pass" placeholder="Enter New Password">
           <input type="password" name="cpass" placeholder="Enter New Password Again">
           <input type="submit" name="submit" value="Submit!">
         </form>
       </div>

       <div class="close-btn" onclick="window.location = 'index.php' "></div>
     </div>
   </body>
 </html>
