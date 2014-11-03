<?php 

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/html; charset=utf-8");

require_once('../code/backbone.php');
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
  $year = $_POST['year'];
  $day = $_POST['day'];
  if(intval($_POST['month']) > 10) {
    $month = '0'.$_POST['month'];  
  }
  else {
    $month = $_POST['month'];  
  }

  $birthday = $year.'-'.$month.'-'.$day;
  
  move_uploaded_file ('images/profile/tmp/'.$_POST['displayPic'] , 'images/profile/');
  
  echo updateProfile(1,$_POST['fName'],$_POST['lName'],$_POST['displayPic'],$_POST['loc'],$_POST['gender'],$birthday,$_POST['bio'],$_POST['occupation']);  
}

if($_POST['type'] == 'temp_profile') {
  
  $new_filename = selectLoggedInAccountID().'.png';
  
  $target_dir = "images/profile/tmp/";
  $target_dir = $target_dir . $new_filename;
  
  if(file_exists($target_dir)) {
    unlink($target_dir);
  }
  
  foreach($_FILES as $file) {
    if (move_uploaded_file($file['tmp_name'], $target_dir)) {
      echo $new_filename;
    } else {

    }
  }
}
?>