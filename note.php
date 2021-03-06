<?php
session_start();
include_once('includes/connection.php');
if(isset($_SESSION['loggedin'])){
if(isset($_GET['id'])){
  $id = trim($_GET['id']);
  $query = $pdo->prepare("select * from notes where note_id = ?");
  $query->bindValue(1, $id);
  $query->execute();
  $data = $query->fetch();
}

if($_SESSION['userid'] == $data['note_user_id']){

 ?>
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
         <h4><?php echo $data['note_title']; ?></h4>
         <p><?php echo $data['note_body']; ?></p>
       </div>

       <div class="close-btn" onclick="window.location = 'index.php' "></div>
     </div>
   </body>
 </html>
<?php
}else{ ?>

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
          <h4>You are not authorized to view this note</h4>
        </div>

        <div class="close-btn" onclick="window.location = 'index.php' "></div>
      </div>
    </body>
  </html>

<?php
}
}else{
  header('Location: index.php');
} ?>
