<?php
require 'authenticate.php';
require_once('includes/MySqlDb.php');
?>

<?php require 'includes/header.php';   ?>
  <div id="main_content">
          <?php   
             
                  if (isset($_GET['welcome']))
                  {
                     echo '<div style="padding:20px;margin:auto;width: 500px;height:160px;"></div><div id="message"><b>Welcome to Prodigybd Result Publisher, '.$_GET['welcome'].'</b></div>';
                  }
              
          ?> 
          
  </div>           
<?php require 'includes/footer.php';   ?>