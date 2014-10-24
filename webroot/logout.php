<?
    
	session_start();
	include_once('../code/scripts/login.php');
    logout();
    header('Location: http://www.konfirmed.com/temp/webroot');
    die();
?>
