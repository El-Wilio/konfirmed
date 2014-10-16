<?php 

require_once('../code/scripts/register.php');
<<<<<<< HEAD
<<<<<<< HEAD
require_once('../code/scripts/login.php');
=======
//require_once('../code/scripts/login.php');
>>>>>>> william
=======
//require_once('../code/scripts/login.php');
=======
require_once('../code/scripts/login.php');
>>>>>>> origin/william
>>>>>>> origin/william

if($_POST['type'] == 'sendRegistrationInfo') {

    echo register($_POST['email'], $_POST['email_confirmation'], $_POST['password'], $_POST['password_confirmation']);

}

if($_POST['type'] == 'checkEmailAvailability') {
    
    if(!checkForAvailableUsername($_POST['email'])) { echo 'This email was already taken';}
    
}

if($_POST['type'] == 'login') {
    
<<<<<<< HEAD
<<<<<<< HEAD
   if(login($_POST['email'], $_POST['password'])) {
=======
   if(login($username, $password)) {
>>>>>>> william
=======
   if(login($username, $password)) {
=======
   if(login($_POST['email'], $_POST['password'])) {
>>>>>>> origin/william
>>>>>>> origin/william
        echo "success";
    }
    else {
        echo "error";
    }
}      


?>