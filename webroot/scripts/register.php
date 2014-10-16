<?php 
	
    function validateEmail($email) {
    
        $regex = '/^\w+@\w+\.\w{2,4}(\.\w{2,4})?$/';
        
        if(preg_match($regex, $email) == 1) return true;
        else return false;
        
    }
    
	function register($email1, $email2, $pass1, $pass2) {
        
		if($email1 != $email2) { $_POST['error'] = 'email confirmation error' ;}
        if(strlen($pass1) < 5) {$_POST['error'] = 'password too short'; }
		if($pass1 != $pass2) {   $_POST['error'] = 'password confirmation error';}
        if(!validateEmail($email1)) { $_POST['error'] = 'email validation error'; } 
		
		if(!checkForAvailableUsername($email1)) {
            $_POST['error'] = 'this email was already taken';
		}
        
        if(!isset($_POST['error'])) { 
        
        	$salt = makeSalt();
			$encryptedPassword = crypt($pass1, $salt);
			makeAccount($email1, $encryptedPassword, $salt);
        
            $_POST['error']= 'noerror'; 
        
        }
		
	}
	
	
	//Start do not call
	function checkForAvailableUsername($username) {
		$con = connectToDatabase();
		$result = mysqli_query($con, "Select * FROM account WHERE username='$username'");
		$found = false;
		while($row = mysqli_fetch_array($result) && !$found) {
            echo $row['username'];
			if($username == $row['username']) {
				$found = true;	
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
	
	function connectToDatabase() {
		$con = mysqli_connect("konfirmedcom.fatcowmysql.com", "cbarrieau", "K0nfirmed12.", "db_konfirmed");
		if(mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();	
		}
		return $con;
	}
?>