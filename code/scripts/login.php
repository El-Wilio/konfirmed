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
		session_start();
		unset($_SESSION['LoggedInAs']);
		session_destroy();
	}

?>