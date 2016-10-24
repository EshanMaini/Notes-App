<?php
session_start();
include_once('includes/connection.php');
if(isset($_GET['note_id']) && $_GET['user_id']){
  $note_id = $_GET['note_id'];
  $user_to_id = $_GET['user_id'];
  $user_from_id = $_SESSION['userid'];

  $query = $pdo->prepare("select * from users where user_id = ?");
  $query->bindValue(1,$user_from_id);
  $query->execute();
  $data = $query->fetch();

  $query1 = $pdo->prepare("select * from notes where note_id = ?");
  $query1->bindValue(1,$note_id);
  $query1->execute();
  $dat = $query1->fetch();

  $noti_body = $data['user_firstname'].' '.$data['user_lastname'].' shared a note with you <br> - '.$dat['note_title'];

  $query1 = $pdo->prepare("insert into notifications (noti_body,noti_user_id,noti_note_id) values(?,?,?)");
  $query1->bindValue(1,$noti_body);
  $query1->bindValue(2,$user_to_id);
  $query1->bindValue(3,$note_id);
  if($query1->execute()){

    $query2 = $pdo->prepare("select * from users where user_id = ?");
    $query2->bindValue(1,$user_to_id);
    $query2->execute();
    $da = $query2->fetch();

    $da['noti_count'] = $da['noti_count'] + 1;

    $query3 = $pdo->prepare("update users set noti_count = ? where user_id = ?");
    $query3->bindValue(1,$da['noti_count']);
    $query3->bindValue(2,$user_to_id);
    $query3->execute();
    header('Location: index.php');
  }else{
    echo "error!";
  }
}




?>
