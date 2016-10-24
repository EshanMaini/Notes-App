<?php
include_once('includes/connection.php');
$pass_success = false;
if(isset($_POST['submit'])){
  if(isset($_POST['email'])){
    $email = $_POST['email'];
    if(!empty($email)){
      $query = $pdo->prepare("select * from users where user_email = ?");
      $query->bindValue(1, $email);
      $query->execute();
      $data = $query->fetch();
      mail($data['user_email'],'Change Password',"Hey,". $data['user_firstname']."\n\n Your account password is \n\n". $data['user_password'],'From:eshan@notesapp.in');
      $pass_success = true;
    }else{
      $error =  "all fields are required";
    }
  }else{
    $error =  "all fields are required";
  }
}


if($pass_success == true){?>
  <!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <title>Notes App</title>
      <link rel="stylesheet" href="css/main.css" media="screen" title="no title" charset="utf-8">
    </head>
    <body>
      <div class="container">
        <header class="logo">
          <a href="index.php"> My Notes App </a>
        </header>

        <div class="inner-cont">
          <p> You will recieve an email with the password , Please check your email <br>
          If you could not find the message in your inbox, check your spam !</p>
        </div>

        <div class="close-btn" onclick="window.location = 'index.php' "></div>
      </div>
    </body>
  </html>
<?php }else {?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Notes APP - Forget Password</title>
    <link rel="stylesheet" href="css/main.css" media="screen" title="no title" charset="utf-8">
  </head>
  <body>
    <div class="container">
      <header class="logo">
        <a href="index.php"> My Notes App </a>
      </header>
      <div class="form-cont">
        <form class="login" action="forget.php" method="post" style="width:100%; border:none;">
          <h4 class= "error"><?php if(isset($error)){echo $error;} ?></h4>
          <input type="email" name="email" placeholder="Enter Your Email Id">
          <input type="submit" name="submit" value="Submit!">
        </form>
      </div>
      <div class="close-btn" onclick="window.location = 'index.php' "></div>
    </div>
  </body>
</html>
<?php } ?>
