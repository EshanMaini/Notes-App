<?php
session_start();
if (isset($_GET['note_id'])){
  $_SESSION['noteid'] = $_GET['note_id'];
}

?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Share Notes</title>

    <script type="text/javascript">
        function findmatch() {
          if(window.XMLHttpRequest){
            xmlhttp = new XMLHttpRequest();
          }else{
            xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
          }

          xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
              document.getElementById('results').innerHTML = xmlhttp.responseText;
            }
          }

          xmlhttp.open('GET','search.php?search_text='+document.search.search_text.value, true);
          xmlhttp.send();

        }
    </script>
    <link rel="stylesheet" href="css/main.css" media="screen" title="no title" charset="utf-8">

  </head>
  <body>
    <div class="container">

      <header class="logo">
        <a href="index.php"> My Notes App </a>
      </header>

      <form id="search" class="inner-cont" name= "search" >
         <div class="search-title">
           Search for the person you want to share the note.
         </div>
        <input class="search-input" type="text" name="search_text" onkeyup="findmatch();">
      </form>

      <div id="results"> </div>

    </div>

  </body>
</html>
