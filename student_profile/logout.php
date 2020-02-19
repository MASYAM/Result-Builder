<?php
session_start();
require('config.php');
function sanitize($data)
{
    $data=trim($data);
    $data=htmlspecialchars($data);
    return $data;
}
$signature= sanitize($_GET['signature']);
if ($signature === $_SESSION['signature']) 
{
    //authenticated user request
    $_SESSION['logged_in'] = False;
    session_destroy();   
    session_unset();
    $redirectback=$domain.'';        
    header(sprintf("Location: %s", $redirectback));	
    exit;

}else 
    {
        //unauthorized logout
        session_destroy();
        session_unset();
        $redirectback=$domain.'/';

        header(sprintf("Location: %s", $redirectback));	
        exit;  
    }
?>