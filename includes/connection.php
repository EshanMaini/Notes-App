<?php
try {
  $pdo = new PDO('mysql:host=localhost;dbname=dbname','user','pass');
} catch (PDOException $e) {
  exit($e);
}


 ?>
