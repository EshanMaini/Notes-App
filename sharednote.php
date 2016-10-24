<?php
session_start();
include_once('includes/connection.php');
if(isset($_SESSION['loggedin'])){
if(isset($_GET['note'])){
  $note_id = trim($_GET['note']);
  $query = $pdo->prepare("select * from notes where note_id = ?");
  $query->bindValue(1, $note_id);
  $query->execute();
  $data = $query->fetch();

}
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
}else{
  header('Location: index.php');
} ?>
