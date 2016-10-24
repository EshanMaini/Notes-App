<?php
include_once('includes/connection.php');

if(isset($_GET['email']) && isset($_GET['email_code'])){
  $email = trim($_GET['email']);
  $code = trim($_GET['email_code']);

  $query = $pdo->prepare("select * from users where user_email = ?");
  $query->bindValue(1,$email);
  $query->execute();
  $data = $query->fetch();

  if($data['code'] == $code){
    $query = $pdo->prepare("update users set active = 1 where user_email = ?");
    $query->bindValue(1, $email);
    if($query->execute()){ ?>
      <!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Notes App - Activation</title>
          <link rel="stylesheet" href="css/main.css" media="screen" title="no title" charset="utf-8">
        </head>
        <body>
          <div class="container">

              <header class="logo">
                <a href="index.php"> My Notes App </a>
              </header>

            <div class="inner-cont">
              <h4 class= "error"><?php if(isset($error)){echo $error;} ?></h4>
              <p> Your Account Has been Successfully Activated, You Can Now Log In. </p>
              <a class="act-login" href="index.php">Log in.</a>
            </div>
            <div class="close-btn" onclick="window.location = 'index.php' "></div>
          </div>
        </body>
      </html>

    <?php  }
  }else{
    $error = "activation code does not match";
  }
}else{
  header('Location: index.php');
  exit();
}

 ?>
