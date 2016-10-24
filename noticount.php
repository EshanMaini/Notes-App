<?php
session_start();
include_once('includes/connection.php');

  $query = $pdo->prepare("update users set noti_count = 0 where user_id = ?");
  $query->bindValue(1, $_SESSION['userid']);
  $query->execute();
  echo "4";



 ?>
