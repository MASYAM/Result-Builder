<?php

///////////////////////////////////////
//START OF USER CONFIGURATION/////////
/////////////////////////////////////

//Define MySQL database parameters

$username = "root";
$password = "";
$hostname = "localhost";
$database = "result_builder_users";

//Define your canonical domain including trailing slash!, example:
$domain= "";

//Define sending email notification to webmaster

$email='';
$subject='New user registration notification';
$from='';

//Define Recaptcha parameters
$privatekey ="";
$publickey = "";

//Define length of salt,minimum=10, maximum=35
$length_salt=15;

//Define the maximum number of failed attempts to ban brute force attackers
//minimum is 5
$maxfailedattempt=5;

//Define session timeout in seconds
//minimum 60 (for one minute)
$sessiontimeout=7200;

////////////////////////////////////
//END OF USER CONFIGURATION/////////
////////////////////////////////////

//DO NOT EDIT ANYTHING BELOW!

$dbhandle = mysqli_connect($hostname, $username, $password, $database)
 or die("Unable to connect to MySQL");
$selected = mysqli_select_db($dbhandle,$database)
  or die("Could not select $database");
$loginpage_url= $domain.'/';
$forbidden_url= $domain.'/403forbidden.php';
?>
