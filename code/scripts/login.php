<?php
<<<<<<< HEAD
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
=======

    include_once(dirname( __FILE__ ).'/../config.php');

<<<<<<< HEAD
	
>>>>>>> origin/william

	function login($username, $password) {
=======
	function login($username, $password) {
        $salt = '';
>>>>>>> origin/william
		$con = connectToDatabase();
		$result = mysqli_query($con, "Select salt From account where username='" . $username . "'");
		while($row = mysqli_fetch_array($result)) {
			$salt = $row['salt'];
		}
		$encryptedPassword = crypt($password, $salt);
<<<<<<< HEAD
		$result = mysqli_query($con, "Select username from account where encrypted_password='" . $encryptedPassword . "'");
=======
		$result = mysqli_query($con, "Select username FROM account where encrypted_password='" . $encryptedPassword . "'");
>>>>>>> origin/william
		$found = false;
		while($row = mysqli_fetch_array($result)) {
			if($row['username'] == $username) { $found = true; }
		}
		
		if($found) {
			session_start();
<<<<<<< HEAD
<<<<<<< HEAD
			$_SESSION['LoggedInAs'] = $username;	
		}
		
=======
			$_SESSION['LoggedInAs'] = $username;
		}
		
        mysqli_close($con);
>>>>>>> william
=======
			$_SESSION['LoggedInAs'] = $username;
=======
			$_SESSION['LoggedInAs'] = $username;	
>>>>>>> origin/william
		}
		
        mysqli_close($con);
>>>>>>> origin/william
		return $found;
	}
	
	function logout() {
		unset($_SESSION['LoggedInAs']);
	}

<<<<<<< HEAD
<<<<<<< HEAD

	function connectToDatabase() {
		$con = mysqli_connect("konfirmedcom.fatcowmysql.com", "cbarrieau", "K0nfirmed12.", "db_konfirmed");
		if(mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();	
		}
		return $con;
	}
=======
=======
>>>>>>> origin/william
	function displayIsLoggedInOld() {
		if(login($_POST['txtLoginUsername'], $_POST['passLoginPass'])) {
			echo "Logged in!";
		} else {
			echo "Not logged in.";
		}
		echo "<a href='../../webroot/test.php'>Return to test.php</a>";
	}

<<<<<<< HEAD
>>>>>>> william
=======
=======
>>>>>>> origin/william
>>>>>>> origin/william
?>