<?php
try{
  $pdo = new PDO('mysql:host=localhost;dbname=urdbname','user','pass');
}catch(PDOException $e){
  exit('Database Error!');
}
 ?>
