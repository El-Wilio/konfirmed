<?php

function updateProfile($showEmail, $fName, $lName, $displayPic,
						$location, $gender, $birthdate, $bio, $occupation) {
	include_once(dirname( __FILE__ ).'/../backbone.php');
	//Load data
	$con = connectToDatabase();
	$result = mysqli_query($con, "Select id from account where username = '" . $_SESSION['LoggedInAs'] . "'");
	$row = mysqli_fetch_array($result);
	$accountID = $row['id'];
	
	$con = connectToDatabase();
	if(!isProfileEmpty()) {
		$command = "UPDATE profile " . 
				"SET showEmail = " . $showEmail . ", " . 
				" fName = '" . $fName . "', " .
				" lName = '" . $lName . "', " .
				" displayPic = '" . $displayPic . "', " .
				" location = '" . $location . "', " .
				" gender = '" . $gender . "', " .
				" birthdate = '" . $birthdate . "', " .
				" bio = '" . $bio . "', " .
				" occupation = '" . $occupation . "'" . 
				" WHERE id_account = '" . $accountID . "'";
		$result = mysqli_query($con, $command);
	} else { 
	$command = "INSERT INTO profile (" . 
									"id_account, showEmail, fName, lName, displayPic, location, " . 
									"gender, birthdate, bio, occupation)" . 
									" VALUES ('" . $accountID . "', '" . 
									$showEmail . "', '" . 
									$fName . "', '" . 
									$lName . "', '" . 
									$displayPic . "', '" . 
									$location . "', '" . 
									$gender . "', '" . 
									$birthdate . "', '" . 
									$bio . "', '" . 
									$occupation . "')";
	$result = mysqli_query($con, $command); 
	}
	$retval = -1;
	if(mysqli_affected_rows($con) == -1) {
		$retval = false;
	} else if(mysqli_affected_rows($con) == 1) {
		$retval = true;
	} else { 
		$retval = -1;
	}
	
	mysqli_close($con);
	return $retval;
}
?>