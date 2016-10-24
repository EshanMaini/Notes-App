<?php
session_start();
include_once('includes/connection.php');
if(isset($_SESSION['loggedin'])){

if(isset($_GET['edit_id'])){
  $edit_id = $_GET['edit_id'];
  $query = $pdo->prepare("select * from notes where note_id = ?");
  $query->bindValue(1,$edit_id);
  $query->execute();
  $data = $query->fetch();
  $title = $data['note_title'];
  $body = $data['note_body'];
  $_SESSION['edit'] = $edit_id;

  if(isset($_POST['add-btn'])){
    if(isset($_POST['title']) && isset($_POST['note-body'])){
      $title = $_POST['title'];
      $msg = $_POST['note-body'];
      if(!empty($title) && !empty($msg)){
        $query = $pdo->prepare("update notes set  note_title=?,note_body=? where note_id = ? ");
        $query->bindValue(1, $title);
        $query->bindValue(2, $msg);
        $query->bindValue(4, $edit_id);
        $query->execute();
  }

}
}

} else if(isset($_POST['add-btn'])){
  if(isset($_POST['title']) && isset($_POST['note-body'])){
    $title = $_POST['title'];
    $msg = $_POST['note-body'];
    if(!empty($title) && !empty($msg)){
      if(isset($_SESSION['edit'])){
        $query = $pdo->prepare("update notes set  note_title=?,note_body=?, note_timestamp=? where note_id = ? ");
        $query->bindValue(1, $title);
        $query->bindValue(2, $msg);
        $query->bindValue(3, time());
        $query->bindValue(4, $_SESSION['edit']);
        $query->execute();
        unset($_SESSION['edit']);
      }else{
        $query = $pdo->prepare("insert into notes (note_title,note_body,note_timestamp,note_user_id) values(?,?,?,?)");
        $query->bindValue(1, $title);
        $query->bindValue(2, $msg);
        $query->bindValue(3, time());
        $query->bindValue(4, $_SESSION['userid']);
        $query->execute();
      }

    }else{
      echo "all fields are required";
    }
  }else{
    echo "all fileds are required";
  }
}
  $query = $pdo->prepare("select * from users where user_id = ?");
  $query->bindValue(1, $_SESSION['userid']);
  $query->execute();
  $user_data = $query->fetch();
 ?>
 <!DOCTYPE html>
 <html>
   <head>
     <meta charset="utf-8">
     <title> <?php echo $user_data['user_firstname'] ?> - My Notes App</title>
     <link rel="stylesheet" href="css/main.css" media="screen" title="no title" charset="utf-8">
     <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css" media="screen" title="no title" charset="utf-8">
     <script type="text/javascript" src="js/jquery.min.js"></script>
     <script type="text/javascript" src="js/script.js"></script>
     <script type="text/javascript">
       function check(){
         if(window.XMLHttpRequest){
           xmlhttp = new XMLHttpRequest();
         }else{
           xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
         }

         xmlhttp.onreadystatechange = function(){
           if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
             document.getElementById('noti-circle').innerHTML = xmlhttp.responseText;
           }
         }

         xmlhttp.open('GET','noticount.php',true);
         xmlhttp.send();

       }
     </script>
   </head>
   <body>
     <div class="container">
       <header class="logo">
         <a style="float:left;" href="index.php"> My Notes App </a>
         <a style="float:right;" class="logout-btn" href="logout.php">Logout</a>
         <a class="pass-link" href="reset.php"> Change Password</a>
         <a onclick="check();" class="noti-link" href="#"> <i class="fa fa-bell fa-lg"></i> </a>
           <?php
           if($user_data['noti_count'] > 0){ ?>
             <span class="noti-circle">
               <?php  echo $user_data['noti_count'] ; ?>
             </span>
          <?php } ?>
         <div class="noti-list">
            <?php
              $query = $pdo->prepare("select * from notifications where noti_user_id = ?");
              $query->bindValue(1,$_SESSION['userid']);
              $query->execute();
              $dat = $query->fetchAll();
              foreach ($dat as $d) { ?>
                <a class="noti" href="sharednote.php?note=<?php echo $d['noti_note_id']; ?>"><?php echo $d['noti_body']; ?></a><br><hr>
              <?php } ?>
         </div>
       </header>
       <div class="notes-cont">
         <div class="notes-list">
           <?php
           $query = $pdo->prepare("select * from notes where note_user_id = ?");
           $query->bindValue(1, $_SESSION['userid']);
           $query->execute();
           $data = $query->fetchAll();

           ?>
           <ul> List Of Notes
           <?php foreach ($data as $note ) { ?>
             <div class="item-cont">
               <a class="item" href= note.php?id=<?php echo $note['note_id']; ?>><li><?php echo $note['note_title']; ?> - <?php echo date('F j' , $note['note_timestamp']); ?></li></a>
             </div>
             <div class="opt-panel">
               <a title="Delete" style="color:#e74c3c;" href="delete.php?id=<?php echo $note['note_id']; ?>"><i class="fa fa-trash fa-lg"></i></a>
               <a title="Edit" style="color:#48a2df;" href="notes.php?edit_id=<?php echo $note['note_id']; ?>"><i class="fa fa-edit fa-lg"></i></a>
               <a title="Share" style="color:#f1c40f;" href="share.php?note_id=<?php echo $note['note_id']; ?>"><i class="fa fa-share-alt fa-lg"></i></a>
             </div>


          <?php  } ?>
          </ul>
         </div>
         <div class="add-note">
           <form class="noteadd" action="notes.php" method="post">
             <input type="text" name="title" placeholder="Enter Note Title" value="<?php if(isset($title)){ echo $title; } ?>">
             <textarea class="txtarea" name="note-body" rows="4" cols="65" placeholder="Add Text..."><?php if(isset($body)){ echo $body; } ?></textarea>
             <input type="submit" name="add-btn" value="Save Note!">
           </form>
         </div>

       </div>
     </div>
   </body>
 </html>
<?php }else{
  header('Location: index.php');
}
?>
