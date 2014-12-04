<?php

    include_once(dirname( __FILE__ ).'/../config.php');

	function login($username, $password) {
        $salt = '';
		$con = connectToDatabase();
		$result = mysqli_query($con, "Select salt From account where username='" . $username . "'");
		while($row = mysqli_fetch_array($result)) {
			$salt = $row['salt'];
		}
		$encryptedPassword = crypt($password, $salt);
		$result = mysqli_query($con, "Select username FROM account where encrypted_password='" . $encryptedPassword . "'");
		$found = false;
		while($row = mysqli_fetch_array($result)) {
			if($row['username'] == $username) { $found = true; }
		}
		
		if($found) {
			session_start();
			$_SESSION['LoggedInAs'] = $username;
		}
		
        mysqli_close($con);
		return $found;
	}
	
	function logout() {
		unset($_SESSION['LoggedInAs']);
		session_destroy();
	}

 
    function initializeProfile($account_id) {
        $success = false;
        $con = connectToDatabase();
        $query = 'INSERT INTO profile (id_account) VALUES ('.$account_id.')';
        $result = mysqli_query($con, $query);
        if($result == true) $success = true;
        mysqli_close($con);
        return $success;
    }
    
?>