<?php 

require_once('../code/scripts/register.php');
require_once('../code/scripts/login.php');
require_once('../code/scripts/editProfile.php');


if($_POST['type'] == 'sendRegistrationInfo') {

    echo register($_POST['email'], $_POST['email_confirmation'], $_POST['password'], $_POST['password_confirmation']);

}

if($_POST['type'] == 'checkEmailAvailability') {
    
    if(!checkForAvailableUsername($_POST['email'])) { echo 'This email was already taken';}
    
}

if($_POST['type'] == 'login') {
   if(login($_POST['email'], $_POST['password'])) {
        echo "success";
    }
    else {
        echo "error";
    }
}

if($_POST['type'] == 'setProfile') {
  echo updateProfile($_POST['showEmail'],$_POST['fName'],$_POST['lName'],$_POST['displayPic'],$_POST['location'],$_POST['gender'],$_POST['birthdate'],$_POST['bio'],$_POST['occupation']);  
}

?>