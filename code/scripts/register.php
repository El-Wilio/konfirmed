<?php 

<<<<<<< HEAD
	$email = $_POST['txtRegisterUsername'];
	$pass = $_POST['passRegisterPassword'];
	$confirmPass = $_POST['passRegisterConfirmPassword'];
	echo register($email, $email, $pass, $confirmPass);
	
	
	function register($email1, $email2, $pass1, $pass2) {
		$errMessage = "";
		if($email1 != $email2) { $errMessage .= "Your email addresses do not match.<br />";}
		if($pass1 != $pass2) { $errMessage .= "Your passwords do not match.<br />";}
		
		if(checkForAvailableUsername($email1)) {
			echo "<p>Username available</p>";
			$salt = makeSalt();
			$encryptedPassword = crypt($pass1, $salt);
			makeAccount($username, $encryptedPassword, $salt);
		} else { $errMessage .= "This username is already taken. <br />"; }
		
		if($errMessage == "") { $errMessage = "success"; }
		return $errMessage;
=======
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
		
>>>>>>> william
	}
	
	
	//Start do not call
	function checkForAvailableUsername($username) {
		$con = connectToDatabase();
<<<<<<< HEAD
		$result = mysqli_query($con, "Select username From account");
		$found = false;
		while($row = mysqli_fetch_array($result)) {				
				if($username == $row['username']) {
					$found = true;	
					break;
				}
=======
		$result = mysqli_query($con, "Select * FROM account WHERE username='$username'");
		$found = false;
		while($row = mysqli_fetch_array($result)) {
			if($username == $row['username']) {
				$found = true;	
                break;
			}
>>>>>>> william
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
	
<<<<<<< HEAD
	function connectToDatabase() {
		$con = mysqli_connect("konfirmedcom.fatcowmysql.com", "cbarrieau", "K0nfirmed12.", "db_konfirmed");
		if(mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();	
		}
		return $con;
	}
=======
>>>>>>> william
?>