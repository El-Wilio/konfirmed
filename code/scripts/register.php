<?php 

    include_once(dirname( __FILE__ ).'/../config.php');

    function validateEmail($email) {
    
        $regex = '/^\w+@\w+\.\w{2,4}(\.\w{2,4})?$/';
        
        if(preg_match($regex, $email) == 1) return true;
        else return false;
        
    }
    
	function register($email1, $email2, $pass1, $pass2) {
        
		if($email1 != $email2) { $errormessage = 'email confirmation error' ;}
        else if(strlen($pass1) < 5) { $errormessage = 'password too short'; }
		else if($pass1 != $pass2) {   $errormessage = 'password confirmation error';}
        else if(!validateEmail($email1)) { $errormessage = 'email validation error'; } 
		
		else if(!checkForAvailableUsername($email1)) {
            $errormessage = 'this email was already taken';
		}
        
        else { 
        
        	$salt = makeSalt();
			$encryptedPassword = crypt($pass1, $salt);
			makeAccount($email1, $encryptedPassword, $salt);
        
            $errormessage = 'noerror'; 
        
        }
        
        return $errormessage;
		
	}
	
	
	//Start do not call
	function checkForAvailableUsername($username) {
		$con = connectToDatabase();
		$result = mysqli_query($con, "Select * FROM account WHERE username='$username'");
		$found = false;
		while($row = mysqli_fetch_array($result)) {
			if($username == $row['username']) {
				$found = true;	
                break;
			}
		}
		mysqli_close($con);
		return !$found;
	}
	
	function makeSalt() {
		$size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB);
	    $iv = mcrypt_create_iv($size, MCRYPT_DEV_RANDOM);
		return $iv;
	}
	
	function makeAccount($username, $encryptedPassword, $salt) {
		$con = connectToDatabase();
		mysqli_query($con, "INSERT INTO account (username, encrypted_password, salt)
			VALUES('" . $username . "', '" . $encryptedPassword . "', '" . $salt . "')");
		mysqli_close($con);
	}
	
?>