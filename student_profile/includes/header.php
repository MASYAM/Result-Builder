<!DOCTYPE html>
<html>
  <head>
    <title>Student Account</title>
    <link rel="shortcut icon" href="../images/student.png" />
    <link href="stylesheets/design.css" media="all" rel="stylesheet" type="text/css" />
    <link href="stylesheets/design2.css" media="all" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="javascripts/jquery.min.js"> </script>
    <script type="text/javascript" src="javascripts/rb_javascripts.js"> </script>
  </head>
  <body>
    <div id="header_nav">
        <table style="margin:2px 0px 10px 20px;float: left;" >
            <?php  
                $set_header_nav =array(
                                   'Home'   => 'home.php?category=Home',
                                   'Account Settings'   => 'account_settings.php?category=Account Settings'
                                 );
                echo '<td style="padding:0px 1px;"><img src="../images/student.png" width="30px" height="30px"></td>';
                foreach ($set_header_nav as $key => $value)
                {
                   if($_GET['category'] == $key)
                   {
                       echo '<td style="padding:0 7px;"><a class="selected_header_nav_link" href="'.$value.'"><b>'.$key.'</b></a></td>';           
                   }else
                       {
                          echo '<td style="padding:0 7px;"><a class="header_nav_link" href="'.$value.'">'.$key.'</a></td>';
                       }
                }
            ?>
        </table>
        
        <table style="margin:1px 10px 10px 0px;float: right;" cellpadding="10">
            <tr><td style="padding:0 7px;"><a class="header_nav_link" href="logout.php" onclick="return confirm('Are you sure want to log out?')">Log out</a></td></tr>
        </table>
    </div>
    <div id="school_heading">
            <?php
                $report_card = $db->find_by_sql("image,school_heading","report_card","id='1'","");            
                date_default_timezone_set('Asia/Dhaka');
            ?>
            <div style="width:90px;height:90px;float: left;">
                <?php echo '<img src="../'.$report_card[0]['image'].'" width="90px" height="90px">'; ?>
            </div>
            <div style="width:550px;height:90px;margin:auto;">
                <?php echo $report_card[0]['school_heading']; ?>
            </div>
    </div>
      
  <div id="main">    