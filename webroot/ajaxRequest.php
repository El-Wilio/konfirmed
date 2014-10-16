<?php 

require_once('../code/scripts/register.php');
<<<<<<< HEAD
require_once('../code/scripts/login.php');
=======
//require_once('../code/scripts/login.php');
>>>>>>> william

if($_POST['type'] == 'sendRegistrationInfo') {

    echo register($_POST['email'], $_POST['email_confirmation'], $_POST['password'], $_POST['password_confirmation']);

}

if($_POST['type'] == 'checkEmailAvailability') {
    
    if(!checkForAvailableUsername($_POST['email'])) { echo 'This email was already taken';}
    
}

if($_POST['type'] == 'login') {
    
<<<<<<< HEAD
   if(login($_POST['email'], $_POST['password'])) {
=======
   if(login($username, $password)) {
>>>>>>> william
        echo "success";
    }
    else {
        echo "error";
    }
}      


?>