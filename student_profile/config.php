<?php

///////////////////////////////////////
//START OF USER CONFIGURATION/////////
/////////////////////////////////////

//Define MySQL database parameters

$username = "root";
$password = "";
$hostname = "localhost";
$database = "pdbdorg_rp_student_users";

//Define your canonical domain including trailing slash!, example:
$domain= "";

//Define sending email notification to webmaster

$email='syam.csse@gmail.com';
$subject='New user registration notification';
$from='From: www.isms.pdbd.org';

//Define Recaptcha parameters
$privatekey ="6LcUquISAAAAAONVSSjZ-njky8wE4RzqHDoWwwTY";
$publickey = "6LcUquISAAAAALopKvD0p0bPAt0jP79ZeSteR1e5";

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

$dbhandle = mysql_connect($hostname, $username, $password)
 or die("Unable to connect to MySQL");
$selected = mysql_select_db($database,$dbhandle)
or die("Could not select $database");
$loginpage_url= $domain.'/';
$forbidden_url= $domain.'/403forbidden.php';
?>