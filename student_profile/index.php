<?php
ob_start();
require('authenticate.php');

if (isset($_SESSION['logged_in'])) 
{ 
  header("Location: home.php?category=Home "); 
  exit;
}

?>
