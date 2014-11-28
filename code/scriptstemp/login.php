<?php
	if(login($_POST['txtLoginUsername'], $_POST['passLoginPass'])) {
		echo "Logged in!";
	} else {
		echo "Not logged in.";
	}
	echo "<a href='../../webroot/test.php'>Return to test.php</a>";

	function login($username, $password) {
		$con = connectToDatabase();
		$result = mysqli_query($con, "Select salt From account where username='" . $username . "'");
		while($row = mysqli_fetch_array($result)) {
			$salt = $row['salt'];
		}
		$encryptedPassword = crypt($password, $salt);
		$result = mysqli_query($con, "Select username from account where encrypted_password='" . $encryptedPassword . "'");
		$found = false;
		while($row = mysqli_fetch_array($result)) {
			if($row['username'] == $username) { $found = true; }
		}
		
		if($found) {
			session_start();
			$_SESSION['LoggedInAs'] = $username;	
		}
		
		return $found;
	}
	
	function logout() {
		unset($_SESSION['LoggedInAs']);
	}


	function connectToDatabase() {
		$con = mysqli_connect("konfirmedcom.fatcowmysql.com", "cbarrieau", "K0nfirmed12.", "db_konfirmed");
		if(mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();	
		}
		return $con;
	}
?>