<?php
$pass = 'pass12345';

function HashPassword($input)
{
//This is secure hashing the consist of strong hash algorithm sha 256 and using highly random salt
//$salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM)); // For PHP 4/5
$salt = bin2hex(random_bytes(32));
$hash = hash("sha256", $salt . $input); 
$final = $salt . $hash; 
return $final;
}

//Insert hash for user
echo $hashedpassword= HashPassword($pass);

    
//matching hash when user enter
$correctpassword = $hashedpassword;
$salt = substr($correctpassword, 0, 64);
$correcthash = substr($correctpassword, 64, 64);

?>

