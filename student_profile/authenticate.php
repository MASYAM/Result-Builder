<?php

//require user configuration and database connection parameters
//Start PHP session

session_start(); 

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
            $redirectback=$domain.'/';
            
            header(sprintf("Location: %s", $redirectback));	
            exit;   
        }

        //Session Lifetime control for inactivity
        //Credits: http://stackoverflow.com/questions/520237/how-do-i-expire-a-php-session-after-30-minutes

        $passed_time = time() - $_SESSION['LAST_ACTIVITY'];
        if ((isset($_SESSION['LAST_ACTIVITY']) && ($passed_time > $sessiontimeout)))  
         {
            session_destroy();   
            session_unset();  

            //redirect the user back to login page for re-authentication

            $redirectback=$domain.'/';
            header(sprintf("Location: %s", $redirectback));
         }
         $_SESSION['LAST_ACTIVITY'] = time(); 

}

//Pre-define validation
$validationresults=TRUE;
$registered=TRUE;
$recaptchavalidation=TRUE;

//Trapped brute force attackers and give them more hard work by providing a captcha-protected page

$iptocheck= $_SERVER['REMOTE_ADDR'];
$iptocheck= mysql_real_escape_string($iptocheck);

if ($fetch = mysql_fetch_array( mysql_query("SELECT `loggedip` FROM `ipcheck` WHERE `loggedip`='$iptocheck'"))) 
  {
        //Already has some IP address records in the database
        //Get the total failed login attempts associated with this IP address

        $resultx = mysql_query("SELECT `failedattempts` FROM `ipcheck` WHERE `loggedip`='$iptocheck'");
        $rowx = mysql_fetch_array($resultx);
        $loginattempts_total = $rowx['failedattempts'];

        If ($loginattempts_total>$maxfailedattempt) 
        {
            //too many failed attempts allowed, redirect and give 403 forbidden.

            header(sprintf("Location: %s", $forbidden_url));	
            exit;
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

    function sanitize($data)
     {
        $data=trim($data);
        $data=mysql_real_escape_string($data);
        return $data;
     }

    $user=sanitize($_POST["user"]);
    $pass= sanitize($_POST["pass"]);

//validate username
if (!($fetch = mysql_fetch_array( mysql_query("SELECT `username` FROM `authentication` WHERE `username`='$user'")))) 
  {
    //no records of username in database
    //user is not yet registered

    $registered=FALSE;
 }

if ($registered==TRUE) 
  {

    //Grab login attempts from MySQL database for a corresponding username
    $result1 = mysql_query("SELECT `loginattempt` FROM `authentication` WHERE `username`='$user'");
    $row = mysql_fetch_array($result1);
    $loginattempts_username = $row['loginattempt'];

 }

if(($loginattempts_username>3) || ($loginattempts_total>3)) 
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
}

//Get correct hashed password based on given username stored in MySQL database

if ($registered==TRUE) 
 {
	
    //username is registered in database, now get the hashed password

    $result = mysql_query("SELECT `password` FROM `authentication` WHERE `username`='$user'");
    $row = mysql_fetch_array($result);
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

                    mysql_query("UPDATE `authentication` SET `loginattempt` = '$loginattempts_username' WHERE `username` = '$user'");

                    //Possible brute force attacker is targeting registered usernames
                    //check if has some IP address records

                    if (!($fetch = mysql_fetch_array( mysql_query("SELECT `loggedip` FROM `ipcheck` WHERE `loggedip`='$iptocheck'")))) 
                     {

                       //no records
                       //insert failed attempts

                       $loginattempts_total=1;
                       $loginattempts_total=intval($loginattempts_total);
                        mysql_query("INSERT INTO `ipcheck` (`loggedip`, `failedattempts`) VALUES ('$iptocheck', '$loginattempts_total')");	
                     } else
                          {
                            //has some records, increment attempts

                             $loginattempts_total= $loginattempts_total + 1;
                             mysql_query("UPDATE `ipcheck` SET `failedattempts` = '$loginattempts_total' WHERE `loggedip` = '$iptocheck'");
                          }
            }

            //Possible brute force attacker is targeting randomly

            if ($registered==FALSE) 
            {
                if (!($fetch = mysql_fetch_array( mysql_query("SELECT `loggedip` FROM `ipcheck` WHERE `loggedip`='$iptocheck'")))) 
                 {

                   //no records
                   //insert failed attempts

                   $loginattempts_total=1;
                   $loginattempts_total=intval($loginattempts_total);
                   mysql_query("INSERT INTO `ipcheck` (`loggedip`, `failedattempts`) VALUES ('$iptocheck', '$loginattempts_total')");	
                 }else 
                     {
                        //has some records, increment attempts

                        $loginattempts_total= $loginattempts_total + 1;
                        mysql_query("UPDATE `ipcheck` SET `failedattempts` = '$loginattempts_total' WHERE `loggedip` = '$iptocheck'");
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
            mysql_query("UPDATE `authentication` SET `loginattempt` = '$loginattempts_username' WHERE `username` = '$user'");
            mysql_query("UPDATE `ipcheck` SET `failedattempts` = '$loginattempts_total' WHERE `loggedip` = '$iptocheck'");

            //Generate unique signature of the user based on IP address
            //and the browser then append it to session
            //This will be used to authenticate the user session 
            //To make sure it belongs to an authorized user and not to anyone else.
            //generate random salt
            function genRandomString() {
            //credits: http://bit.ly/a9rDYd
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
            
//            $query_welcome_message = mysql_query("SELECT welcome_message FROM authentication WHERE username='$user' ") or die(mysql_error());
//            $result_query_welcome_message = mysql_fetch_array($query_welcome_message);
//            $welcome_message = $result_query_welcome_message['welcome_message'];
//            $_SESSION['welcome_message'] = $welcome_message;
            
      }
}//end of if ((isset($_POST["pass"])) && (isset($_POST["user"])) && ($_SESSION['LAST_ACTIVITY']==FALSE))


if (!$_SESSION['logged_in']): 
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Student Account Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

     <link rel="stylesheet" href="stylesheets/login_design.css" />
     <link rel="shortcut icon" href="images/student_icon.png" />
     <link href='http://fonts.googleapis.com/css?family=Trade+Winds' rel='stylesheet' type='text/css'>
     <link href='http://fonts.googleapis.com/css?family=Electrolize' rel='stylesheet' type='text/css'>
     <meta name="keywords" content="sms, prodigy, isms">
     <meta name="description" content="sms sending software">
     <meta name="author" content="prodigy">
     <script src='javascripts/jquery.min.js' type='text/javascript'></script>	
     <script>
	$(window).bind("load", function() {
	    $('#loading').fadeOut(4000);
	});
	</script>
	<style>
	#loading {
	background:#000 url("images/load8.gif") no-repeat center center;
	height: 100%; width: 100%;
	position: absolute; left:0px; top:0; z-index: 1000;
	}

        </style>
    
   </head>

<body >
     <div id="logIn_box">
<div id="loading"></div>
 <div id="header"> 
      <img src="images/student_icon.png" style="float:left;margin:5px 0 0 120px" width="75px" height="75px">  
      <p class="logoName" style="float:left;margin:20px 0 0 20px;"><b>STUDENT ACCOUNT LOGIN</b></p>       
      
 </div>
 
<div id="middle">
        <img src="images/pdbd-logo.png" style="position:absolute;z-index:100;margin:350px 0 0 650px;" width="130px" height="50px"> 
        <img src="images/books3.gif" style="position:absolute;z-index:100;margin:60px 0 0 80px;" width="220px" height="220px">
	<img src="images/hatandbooks.png" style="position:absolute;z-index:100;margin:150px 0 0 300px;" width="190px" height="190px">
	     <div id="accountForm"
                  <?php
                    if (($loginattempts_username > 3) || ($loginattempts_total>3)) 
                    { 
                     echo 'style="background: url(images/page-blank2.png) no-repeat center;"' ;
                   }else{
                     echo 'style="background: url(images/page-blank.png) no-repeat center;"' ;  
                    } ?>
                  >
                    <?php if ($validationresults==FALSE) echo '<div id="message" style="width:295px;height:35px;margin:0px auto auto 0px;color:#FF3300;font-size:14px;"><b>Please provide valid username, password or captcha ( if required )</b></div>'; ?>			    

	         <div id="logIn_header"
                   <?php
                    if (($loginattempts_username > 3) || ($loginattempts_total>3)) 
                    { 
                     echo 'style="margin: 35px auto auto auto;"' ;
                   }else{
                     echo 'style="margin: 120px auto auto auto;"' ;  
                    } ?>   
                 >Log In</div>    
			    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">					    	      
			      <table style="margin:0px auto auto 69px;" cellpadding="4"> 
			         <tr>
			            <td> <p style="color:rgba(0,0,0,.7);font-family:calibri;font-size:11px;margin:0px;">Username <input type="text" class="input_field" style="margin-left:7px;" name="user" autofocus> </p></td>
			         </tr>
			         <tr>		      
			            <td><p style="color:rgba(0,0,0,.7);font-family:calibri;font-size:11px;margin:0px;">Password <input type="password" class="input_field" style="margin-left:10px;" name="pass" ></p></td>
			         </tr>
                                 <?php
                                if (($loginattempts_username > 3) || ($loginattempts_total>3)) 
                                { 
                                ?>
                                  <tr>
                                   <td>
                                  <span style="color:#000;font-family: calibri;font-size: 13px;"><b>Type the captcha below:</b></span>
                                  <?php
                                  require_once('recaptchalib.php');
                                  echo recaptcha_get_html($publickey);
                                  ?>
                                   </td>
                                  </tr>
                                 <?php } ?>
			         <tr>
			            <td style="text-align:center;"><input type="submit" style="float:right;" class="logIn" value="Log In"></td>
			         </tr>
			       </table>   
			    </form>

	    </div>
</div>	 
	           <div id="footer">Powered by Prodigybd. &nbsp;&nbsp; Web:www.pdbd.org &nbsp;&nbsp; Contact: 01553737666 </div>  
</div> 
</body>
</html>
                 
<?php
exit();
endif;
?>
      