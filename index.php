<?php
session_start();
include_once('includes/connection.php');
$reg_success = false;
if(isset($_SESSION['loggedin'])){
  header('Location:notes.php');
} else if(isset($_POST['login'])){
  //LOGIN
  if(isset($_POST['email']) && isset($_POST['pass'])){
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    if(empty($email) or empty($pass)){
      $error =  "All fields are required";
    }else{
      $query = $pdo->prepare("select * from users where user_email = ? and user_password = ?");
      $query->bindValue(1, $email);
      $query->bindValue(2, $pass);
      $query->execute();
      $user_data = $query->fetch();
      $num = $query->rowCount();

      if($num == 1){
        if($user_data['active'] == 1){
          $_SESSION['loggedin'] = true;
          $_SESSION['userid'] = $user_data['user_id'];
          header('Location:notes.php');
          exit();
        }else{
          $error =  "Account not activated";
        }
      }else{
        $error = "wrong username and password combination!";
      }
    }
  }


}else if(isset($_POST['register'])){
  //REGISTER
  if(isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['cpass'])){
    $firstname = $_POST['fname'];
    $lastname = $_POST['lname'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];

    if(!empty($firstname) && !empty($lastname) && !empty($email) && !empty($pass) && !empty($cpass)){
      //check if user allready exists
      $query = $pdo->prepare("select * from users where user_email=?");
      $query->bindValue(1,$email);
      $query->execute();
      $row = $query->rowCount();
      if($row == 1){
        $error = "Email allready registered";
      }else{
        //password validations
        if($pass == $cpass && strlen($pass) >= 8){
          //insert data into database
          $query = $pdo->prepare("insert into users (user_firstname,user_lastname,user_email,user_password,code) values(?,?,?,?,?)");
          $query->bindValue(1,$firstname);
          $query->bindValue(2,$lastname);
          $query->bindValue(3,$email);
          $query->bindValue(4,$pass);
          $query->bindValue(5,md5($firstname + time()));

          if($query->execute()){
            $query = $pdo->prepare("select * from users where user_email = ?");
            $query->bindValue(1,$email);
            $query->execute();
            $data = $query->fetch();

            //send email for verification

            mail($data['user_email'],'Activate Your Notes App Account.',"Hey, ".$data['user_firstname']." ".$data['user_lastname']."\n\n To activate your Notes App account, click on the link below. \n\n http://mynotesapp.in/activate.php?email=".$data['user_email']."&email_code=".$data['code']."\n\n - Notes App Team.",'From:eshan@mynotesapp.in');
            $reg_success = true;
          }else{
            $error = "something went wrong";
          }
        }else {
          $error = "Password does not match or password length is too short!";
        }
      }
    }else{
      $error = "All fields are required";
    }
  }else{
    $error = "All fields are required";
  }



}





if($reg_success == true) { ?>

  <!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <title>Notes App</title>
      <link rel="stylesheet" href="css/main.css" media="screen" title="no title" charset="utf-8">
      <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css" media="screen" title="no title" charset="utf-8">
    </head>
    <body>
      <div class="container">
        <header class="logo">
          <a href="index.php"> My Notes App </a>
        </header>

        <div class="inner-cont">
          <p>You have been successfully registered, to activate your account please check your email <br>
          If you could not find the message in your inbox, check your spam !</p>
        </div>

        <div class="close-btn" onclick="window.location = 'index.php' "></div>
      </div>
    </body>
  </html>



<?php }else{ ?>
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
      <div class="form-cont">
        <h4 class= "error"><?php if(isset($error)){echo $error;} ?></h4>
        <form class="login" action="index.php" method="post">
          <h4>Log In!</h4>
          <input type="text" name="email" placeholder="Enter Email"/>
          <input type="password" name="pass" placeholder="Enter Password"/>
          <input type="submit" name="login" value="Log In!"/>
          <a href="forget.php">Forgot Password?</a>
        </form>

        <form class="register" action="index.php" method="post">
          <h4>Sign Up!</h4>
          <input type="text" name="fname" placeholder="Enter first name">
          <input type="text" name="lname" placeholder="Enter last name">
          <input type="email" name="email" placeholder="Enter a valid email">
          <input type="password" name="pass" placeholder="Enter a password">
          <input type="password" name="cpass" placeholder="Confirm password">
          <input type="submit" name="register" value="Sign Up!">

        </form>


      </div>
    </div>

  </body>
</html>
<?php } ?>
