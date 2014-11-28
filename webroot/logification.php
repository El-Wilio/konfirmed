<?php 

require_once('../code/scripts/login.php');

    if(login($username, $password)) {
        echo 'logged in'; }
    else {
        echo 'not logged in';
    }

?>