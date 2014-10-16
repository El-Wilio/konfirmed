<?php
<<<<<<< HEAD
	if(login($_POST['txtLoginUsername'], $_POST['passLoginPass'])) {
		echo "Logged in!";
	} else {
		echo "Not logged in.";
	}
	echo "<a href='../../webroot/test.php'>Return to test.php</a>";
=======

    include_once(dirname( __FILE__ ).'/../config.php');

	
>>>>>>> william

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
<<<<<<< HEAD
			$_SESSION['LoggedInAs'] = $username;	
		}
		
=======
			$_SESSION['LoggedInAs'] = $username;
		}
		
        mysqli_close($con);
>>>>>>> william
		return $found;
	}
	
	function logout() {
		unset($_SESSION['LoggedInAs']);
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
	function displayIsLoggedInOld() {
		if(login($_POST['txtLoginUsername'], $_POST['passLoginPass'])) {
			echo "Logged in!";
		} else {
			echo "Not logged in.";
		}
		echo "<a href='../../webroot/test.php'>Return to test.php</a>";
	}

>>>>>>> william
?>