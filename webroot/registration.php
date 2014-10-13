<?php 

require_once('../code/scripts/register.php');

if($_POST['type'] == 'sendRegistrationInfo') {

    echo register($_POST['email'], $_POST['email_confirmation'], $_POST['password'], $_POST['password_confirmation']);

}

else if($_POST['type'] == 'checkEmailAvailability') {
    
    if(!checkForAvailableUsername($_POST['email'])) { echo 'This email was already taken';}
    
}

?>