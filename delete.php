<?php
include_once('includes/connection.php');
if(isset($_GET['id'])){
$id = $_GET['id'];
$query = $pdo->prepare("Delete from notes where note_id=? ");
$query->bindValue(1,$id);
if($query->execute()){
  header('Location:notes.php');
}
}
?>
