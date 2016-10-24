<?php
session_start();
include_once('includes/connection.php');
if (isset($_GET['search_text'])) {
   $search_text = $_GET['search_text'];
}
if (!empty($search_text)){
$query = $pdo->prepare("select * from users where user_firstname like :query ");
$query->bindValue(':query',$search_text.'%');
$query->execute();
$data = $query->fetchAll();
foreach ($data as $d ) {?>
  <a href="sendnoti.php?note_id=<?php echo $_SESSION['noteid'] ?>&user_id=<?php echo $d['user_id'] ?>" style="text-decoration:none; color:#2ecc71"><?php echo $d['user_firstname'].' '.$d['user_lastname'] ?></a><br><hr>

<?php }
}
 ?>
