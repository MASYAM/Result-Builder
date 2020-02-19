<?php
require('authenticate.php');

if (isset($_SESSION['logged_in'])) 
{ 
  header("Location: home.php?category=Home&&welcome=".$_SESSION['welcome_message']." "); 
  exit;
}

?>
