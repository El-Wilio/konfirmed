<?   
	include_once('../code/scripts/login.php');
	session_start();
    logout();
    header('Location: http://www.konfirmed.com/temp/webroot');
    die();
?>
