<?php

//require user configuration and database connection parameters
//Start PHP session

session_start(); 
ini_set('display_errors',0); // Hide all errors

//require user configuration and database connection parameters
require('config.php');

if (($_SESSION['logged_in'])==TRUE) 
  {
        //valid user has logged-in to the website

        //Check for unauthorized use of user sessions

        $iprecreate = $_SERVER['REMOTE_ADDR'];
        $useragentrecreate = $_SERVER["HTTP_USER_AGENT"];
        $signaturerecreate = $_SESSION['signature'];

        //Extract original salt from authorized signature

        $saltrecreate = substr($signaturerecreate, 0, $length_salt);

        //Extract original hash from authorized signature

        $originalhash = substr($signaturerecreate, $length_salt, 40);

        //Re-create the hash based on the user IP and user agent
        //then check if it is authorized or not

        $hashrecreate= sha1($saltrecreate.$iprecreate.$useragentrecreate);

        if (!($hashrecreate==$originalhash)) 
        {

            //Signature submitted by the user does not matched with the
            //authorized signature
            //This is unauthorized access
            //Block it
            session_destroy();
            session_unset();
            $redirectback=$domain.'///';
            
            header(sprintf("Location: %s", $redirectback));	
            exit;   
        }

         $_SESSION['LAST_ACTIVITY'] = time(); 

}

//Pre-define validation
$validationresults=TRUE;
$registered=TRUE;
$recaptchavalidation=TRUE;

//Trapped brute force attackers and give them more hard work by providing a captcha-protected page

$iptocheck= $_SERVER['REMOTE_ADDR'];
$iptocheck= mysqli_real_escape_string($dbhandle, $iptocheck);

    if ($fetch = mysqli_fetch_array( mysqli_query($dbhandle,"SELECT `loggedip` FROM `ipcheck` WHERE `loggedip`='".$iptocheck."'")))
  {
        //Already has some IP address records in the database
        //Get the total failed login attempts associated with this IP address

        $resultx = mysqli_query($dbhandle, "SELECT `failedattempts` FROM `ipcheck` WHERE `loggedip`='".$iptocheck."'");
        $rowx = mysqli_fetch_array($resultx);
        $loginattempts_total = $rowx['failedattempts'];

        If ($loginattempts_total>$maxfailedattempt)
        {
            //too many failed attempts allowed, redirect and give 403 forbidden.

            //header(sprintf("Location: %s", $forbidden_url));
            //exit;
        }
  }

//Check if a user has logged-in

if (!isset($_SESSION['logged_in'])) 
  {
    $_SESSION['logged_in'] = FALSE;
  }

//Check if the form is submitted

if ((isset($_POST["pass"])) && (isset($_POST["user"])) && ($_SESSION['LAST_ACTIVITY']==FALSE)) 
{

//Username and password has been submitted by the user
//Receive and sanitize the submitted information

     $user= filter_var($_POST["user"], FILTER_SANITIZE_STRING);
     $pass= filter_var($_POST["pass"], FILTER_SANITIZE_STRING);

//validate username
if (!($fetch = mysqli_fetch_array( mysqli_query($dbhandle,"SELECT `username` FROM `authentication` WHERE `username`='".$user."'"))))
{
  //no records of username in database
  //user is not yet registered

  $registered=FALSE;
}

if ($registered==TRUE)
 {

   //Grab login attempts from MySQL database for a corresponding username
   $result1 = mysqli_query($dbhandle, "SELECT `loginattempt` FROM `authentication` WHERE `username`='".$user."'");
   $row = mysqli_fetch_array($result1);
   $loginattempts_username = $row['loginattempt'];
    
}

/*if(($loginattempts_username>3) || ($loginattempts_total>3)) 
  {
        //Require those user with login attempts failed records to 
        //submit captcha and validate recaptcha

        require_once('recaptchalib.php');
        $resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
        if (!$resp->is_valid) {

        //captcha validation fails

        $recaptchavalidation=FALSE;
        }else 
            {
                $recaptchavalidation=TRUE;	
            }
}*/

//Get correct hashed password based on given username stored in MySQL database

if ($registered==TRUE)
{
   //username is registered in database, now get the hashed password

   $result = mysqli_query($dbhandle,"SELECT `password` FROM `authentication` WHERE `username`='".$user."'");
   $row = mysqli_fetch_array($result);
   $correctpassword = $row['password'];
   $salt = substr($correctpassword, 0, 64);
   $correcthash = substr($correctpassword, 64, 64);
   $userhash = hash("sha256", $salt . $pass);

 }
  if ((!($userhash == $correcthash)) || ($registered==FALSE) || ($recaptchavalidation==FALSE))
     {

             //user login validation fails

             $validationresults=FALSE;

             //log login failed attempts to database

             if ($registered==TRUE)
             {
                     $loginattempts_username= $loginattempts_username + 1;
                     $loginattempts_username=intval($loginattempts_username);

                     //update login attempt records

                 mysqli_query("UPDATE `authentication` SET `loginattempt` = '".$loginattempts_username."' WHERE `username` = '".$user."'");

                     //Possible brute force attacker is targeting registered usernames
                     //check if has some IP address records

                 if (!($fetch = mysqli_fetch_array( mysqli_query($dbhandle,"SELECT `loggedip` FROM `ipcheck` WHERE `loggedip`='".$iptocheck."'"))))
                      {

                        //no records
                        //insert failed attempts

                        $loginattempts_total=1;
                        $loginattempts_total=intval($loginattempts_total);
                          mysqli_query($dbhandle,"INSERT INTO `ipcheck` (`loggedip`, `failedattempts`) VALUES ('".$iptocheck."', '".$loginattempts_total."')");
                      } else
                           {
                             //has some records, increment attempts

                              $loginattempts_total= $loginattempts_total + 1;
                               mysqli_query($dbhandle,"UPDATE `ipcheck` SET `failedattempts` = '".$loginattempts_total."' WHERE `loggedip` = '".$iptocheck."'");
                           }
             }

             //Possible brute force attacker is targeting randomly

             if ($registered==FALSE)
             {
                 if (!($fetch = mysqli_fetch_array( mysqli_query($dbhandle,"SELECT `loggedip` FROM `ipcheck` WHERE `loggedip`='".$iptocheck."'"))))
                  {

                    //no records
                    //insert failed attempts

                    $loginattempts_total=1;
                    $loginattempts_total=intval($loginattempts_total);
                      mysqli_query($dbhandle,"INSERT INTO `ipcheck` (`loggedip`, `failedattempts`) VALUES ('".$iptocheck."', '".$loginattempts_total."')");
                  }else
                      {
                         //has some records, increment attempts

                         $loginattempts_total= $loginattempts_total + 1;
                          mysqli_query($dbhandle,"UPDATE `ipcheck` SET `failedattempts` = '".$loginattempts_total."' WHERE `loggedip` = '".$iptocheck."'");
                      }
              }
   }else
       {
             //user successfully authenticates with the provided username and password

             //Reset login attempts for a specific username to 0 as well as the ip address

             $loginattempts_username=0;
             $loginattempts_total=0;
             $loginattempts_username=intval($loginattempts_username);
             $loginattempts_total=intval($loginattempts_total);
           mysqli_query($dbhandle,"UPDATE `authentication` SET `loginattempt` = '".$loginattempts_username."' WHERE `username` = '".$user."'");
           mysqli_query($dbhandle,"UPDATE `ipcheck` SET `failedattempts` = '".$loginattempts_total."' WHERE `loggedip` = '".$iptocheck."'");

             //Generate unique signature of the user based on IP address
             //and the browser then append it to session
             //This will be used to authenticate the user session
             //To make sure it belongs to an authorized user and not to anyone else.
             //generate random salt
             function genRandomString() {
                 $length = 50;
                 $characters = "0123456789abcdef";
                 for ($p = 0; $p < $length ; $p++) {
                     $string .= $characters[mt_rand(0, strlen($characters))];
                 }

                 return $string;
             }
             $random=genRandomString();
             $salt_ip= substr($random, 0, $length_salt);

             //hash the ip address, user-agent and the salt
             $useragent=$_SERVER["HTTP_USER_AGENT"];
             $hash_user= sha1($salt_ip.$iptocheck.$useragent);

             //concatenate the salt and the hash to form a signature
             $signature= $salt_ip.$hash_user;

             //Regenerate session id prior to setting any session variable
             //to mitigate session fixation attacks

             session_regenerate_id();

             //Finally store user unique signature in the session
             //and set logged_in to TRUE as well as start activity time

             $_SESSION['signature'] = $signature;
             $_SESSION['logged_in'] = TRUE;
             $_SESSION['LAST_ACTIVITY'] = time();
             $_SESSION['username'] = $user;
             ini_set('max_execution_time', 1800); //3600 seconds = 60 minutes
             ini_set("memory_limit","1500M");
             
             $query_welcome_message = mysqli_query($dbhandle,"SELECT welcome_message FROM authentication WHERE username='".$user."' ") or die(mysql_error());
             $result_query_welcome_message = mysqli_fetch_array($query_welcome_message);
             $welcome_message = $result_query_welcome_message['welcome_message'];
             $_SESSION['welcome_message'] = $welcome_message;
             
       }
 }//end of if ((isset($_POST["pass"])) && (isset($_POST["user"])) && ($_SESSION['LAST_ACTIVITY']==FALSE))


if (!$_SESSION['logged_in']): 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <title>Admin LogIn</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">

	<link rel="shortcut icon" href="images/Logofinal.ico" />	
        <link href='http://fonts.googleapis.com/css?family=Trade+Winds' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Electrolize' rel='stylesheet' type='text/css'>

	<link href="stylesheets/login_design.css" type="text/css" media="screen" rel="stylesheet">
        <style type="text/css">
	img, div { behavior: url(iepngfix.htc) }
	</style>
        <link rel="stylesheet" href="stylesheets/jquery-ui.css" />
        <script src="javascripts/jquery-1.9.1.js"></script>
        <script src="javascripts/jquery-ui.js"></script>
        <style>
        label {
        display: inline-block; width: 5em;
        }
        fieldset div {
        margin-bottom: 2em;
        }
        
        .ui-tooltip {
        width: 210px;
        }
        </style>
        <script>
        $(function() {
          $( "[title]" ).tooltip();
        });
        </script>

	</head>
	<body id="login">
		<div id="wrappertop"></div>
			<div id="wrapper">
					<div id="content">
						<div id="header" style="box-shadow:-2px 0px 5px rgba(0,0,0,.3);">
                                                    <span style="color:#fff;font-size:26px;font-weight:bold;font-family: 'Electrolize', cursive;"> Result Builder</span>
						</div>
                                            <div style="height:30px;box-shadow:-2px 2px 5px rgba(0,0,0,.3);">
                                              
                                            </div>
						<div id="darkbanner" class="banner320">
							<h2 style="color:#e2d9b7;font-weight:bold;font-family: 'Electrolize', cursive;font-size:25px;">Admin Login</h2>
						</div>
						<div id="darkbannerwrap">
						</div>
                                            <div style="box-shadow:-2px 0px 5px rgba(0,0,0,.3);"> 
						<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
						<fieldset class="form">
                        	                        <p>
								<label for="user_name" style="margin-left: 6px;font-size:13px;font-weight:bold;font-family: 'Electrolize', cursive;">Username:</label>
								<input type="text" class="<?php if ($validationresults==FALSE) echo "invalid"; else echo "input_box"; ?>" id="user" name="user" >
							</p>
							<p>
								<label for="user_password" style="margin-left: 6px;font-size:13px;font-weight:bold;font-family: 'Electrolize', cursive;">Password:</label>
								<input name="pass" type="password" class="<?php if ($validationresults==FALSE) echo "invalid"; else echo "input_box"; ?>" id="pass" >
							</p>
                                                        <?php
                                                            //if (($loginattempts_username > 3) || ($loginattempts_total>3)) 
                                                            //{ 
                                                          ?>
                                                          <!--<table>
                                                          <tr>
                                                           <td>
                                                          <span style="color:#999;"><b>Type the captcha below:</b></span>-->
                                                          <?php
                                                          //require_once('recaptchalib.php');
                                                          //echo recaptcha_get_html($publickey);
                                                          ?>
                                                           <!--</td>
                                                          </tr>
                                                          </table>-->
                                                         <?php //} ?>
							<button type="submit" class="positive" name="Submit" style="margin-left: 86px;font-weight:bold;font-family: 'Electrolize', cursive;">
								<img src="images/key.png" alt="Announcement">Login
                                                        </button>
                            			</fieldset>
						
						
					        </form>
                                              <?php if ($validationresults==FALSE) echo '<br><div id="message">Please enter valid username, password or captcha ( if required )</div>'; ?>
                                           </div>
                                       </div>
				</div>   

<div id="wrapperbottom_branding">
    
</div>
            
        
</body></html>
<?php
exit();
endif;
?>
