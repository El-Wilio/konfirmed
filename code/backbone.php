<?php
//INCLUDES
include_once(dirname( __FILE__ ).'/config.php');

$classes = scandir("../code/classes");

foreach($classes as $filename) {
	if($filename != "." && $filename != "..") {
		include("../code/classes/" . $filename);	
	}
}

//Run this at the start of every page
session_start();


function isLoggedIn() {
	return isset($_SESSION['LoggedInAs']);
}

function isProfileEmpty() {
	if (isset($_SESSION['LoggedInAs'])) {;
		$con = connectToDatabase();
		$result = mysqli_query($con, "Select id from account where username = '" . $_SESSION['LoggedInAs'] . "'");
		$row = mysqli_fetch_array($result);
		
		$result = mysqli_query($con, "Select count(id) as 'HasProfile' From profile where id_account = '" . $row['id'] . "'");
		//$result = mysqli_query($con, "Select count(id) as 'HasProfile' From profile where id_account = '123456789'");
		$row = mysqli_fetch_array($result);
		$isProfileEmpty = true;
		if($row['HasProfile']) {
			$isProfileEmpty = false;
		}
		
		mysqli_close($con);	
		return $isProfileEmpty;
	} else { return false; }
}

//Select
function selectProfile($accountID) {
	$con = connectToDatabase();
	$result = mysqli_query($con, "Select * from profile where id_account = '" . $accountID . "'");
	$profile = mysqli_fetch_array($result, MYSQLI_NUM);
	mysqli_close($con);
	return $profile;
}

function selectCurrentProfile() {
	$con = connectToDatabase();
	$result = mysqli_query($con, "Select id from account where username = '" . $_SESSION['LoggedInAs'] . "'");
	$row = mysqli_fetch_array($result);
	$accountID = $row['id'];
	mysqli_close($con);
	return selectProfile($accountID);
}


/*	If you do a foreach($submissions as $submission) then:
	
	The following are accessible from the return value: 
	$submission['id']
	$submission['id_account']
	$submission['id_medium']
	$submission['title']
	$submission['date_submitted']
	$submission['filename']
	$submission['extension']
	$submission['medium']
	$submission['genres'] (another array, index based, no keys)
	$submission['tags'] (another array, index based, no keys)
	$submission['rating'] (returns a decimal from 0 to 5)
	
*/
function selectAccountSubmissions($accountID) {
	$con = connectToDatabase();
	//1. Get all submissions 
	$result = mysqli_query($con, "Select * from submission where id_account = '" . $accountID . "' order by date_submitted asc");
	//2. Put each one into the array that is to be returned to the front end
	$submissions = array();
	while($submission = mysqli_fetch_array($result)) {
		//Also get the aggregate data: 
		//	medium, genres, tags, and rating
		$mediumText = selectMedium($submission['id_medium']);
		$submission['medium'] = $mediumText;
		$genres = selectGenres($submission['id']);
		$submission['genres'] = $genres;
		$tags = selectTags($submission['id']);
		$submission['tags'] = $tags;
		$rating = getRating($submission['id']);
		$submission['rating'] = $rating;
		array_push($submissions, $submission);
	}
	mysqli_close($con);
	return $submissions;
}

function selectMedium($idMedium) {
	$con = connectToDatabase();
	$result = mysqli_query($con, "Select name from medium where id = " . $idMedium);
	$row = mysqli_fetch_array($result);
	mysqli_close($con);
	return $row['name'];
}

function selectGenres($idSubmission) {
	$con = connectToDatabase();
	$result = mysqli_query($con, "Select id_genre from submission_genre where id_submission = " . $idSubmission);
	$genres = array();
	while($row = mysqli_fetch_array($result)) {
		$innerResult = mysqli_query($con, "Select name from genre where id = " . $row['id_genre']);
		$row = mysqli_fetch_array($innerResult);
		array_push($genres, $row['name']);
	}
	mysqli_close($con);
	return $genres;
}

function selectTags($idSubmission) {
	$con = connectToDatabase();
	$result = mysqli_query($con, "Select id_tag from submission_tag where id_submission = " . $idSubmission);
	$tags = array();
	while($row = mysqli_fetch_array($result)) {
		$innerResult = mysqli_query($con, "Select name from tag where id = " . $row['id_tag']);
		$row = mysqli_fetch_array($innerResult);
		array_push($tags, $row['name']);
	}
	mysqli_close($con);
	return $tags;
}

function getRating($idSubmission) {
	$con = connectToDatabase();
	$result = mysqli_query($con, "Select avg(rating) as avgRating from rating where id_submission = " . $idSubmission);
	$row = mysqli_fetch_array($result);
	$avgRating = $row['avgRating'];
	mysqli_close($con);
	return $avgRating;
}

function interpretDate($theDate) {
	$months = array("Error", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	$year = substr($theDate, 0, 4);
	$month = ltrim(substr($theDate, 5, 2), '0');
	$date = ltrim(substr($theDate, 8, 2), '0');
	
	$st = array("1", "21", "31");
	$nd = array("2", "22");
	$rd = array("3", "23");
	
	if(in_array($date, $st)) { 
		$date .= "st";
	} else if (in_array($date, $nd)) {
		$date .= "nd";
	} else if (in_array($date, $rd)) {
		$date .= "rd";	
	} else { 
		$date .= "th";
	}
	
	return $months[$month] . " " . $date . " " . $year;
}

function selectProfilesByMedium($idMedium) {
	$con = connectToDatabase();
	//First get all the account IDs that have this mediumID
	$result = mysqli_query($con, "Select id_account from account_medium where id_medium = " . $idMedium);
	$accounts = array();
	while($row = mysqli_fetch_array($result)) {
		array_push($accounts, $row['id_account']);
	}
	$profiles = array();
	foreach($accounts as $idAccount) {
		$profile = selectProfile($idAccount);
		array_push($profiles, $profile);
	}
	mysqli_close($con);
	return $profiles;
}

function selectAccountMediums($accountID) {
	$con = connectToDatabase();
	$result = mysqli_query($con, "Select id_medium from account_medium where id_account = " . $accountID);
	$mediums = array();
	while($row = mysqli_fetch_array($result)) {
		$mediumResult = mysqli_query($con, "Select name from medium where id = " . $row['id_medium']);
		array_push($mediums, $mediumResult['name']);
	}
	mysqli_close($con);
	return $mediums;
}

function insertAccountMedium($accountID, $mediumID) {
	$con = connectToDatabase();
	mysqli_query($con, "Insert into account_medium (id_medium, id_account)
		VALUES('" . $mediumID . "', '" . $accountID . "')");
	mysqli_close($con);
	if(mysqli_affected_rows() == -1) {
		return false;
	} else if(mysqli_affected_rows() == 1) {
		return true;
	} else { 
		return -1;
	}
}

function deleteAccountMedium($accountID, $mediumID) {
	$con = connectToDatabase();
	mysqli_query($con, "Delete from account_medium where id_account = " . $accountID . " and id_medium = " . $mediumID);
	mysqli_close($con);
	if(mysqli_affected_rows() == -1) {
		return false;
	} else if(mysqli_affected_rows() == 1) {
		return true;
	} else { 
		return -1;
	}
}

function insertRating($submissionID, $accountID, $rating) {
	$con = connectToDatabase();
	mysqli_query($con, "Insert into rating (id_submission, id_account, rating)
				VALUES ('" . $submissionID . "', '" . $accountID . "', '" . $rating . "')");
	mysqli_close($con);
	if(mysqli_affected_rows() == -1) {
		return false;
	} else if(mysqli_affected_rows() == 1) {
		return true;
	} else { 
		return -1;
	}
}

/*
Required format for the $submissionData array:
	$submissionData['id_account']
	$submissionData['id_medium']
	$submissionData['title']
	$submissionData['filename']
	$submissionData['extension']
	$submissionData['genres'] (another array, indexed, not key-valued)
	$submissionData['tags'] (another array, indexed, not key-valued. All tags should be just the string for the tag.)
	*/
function insertSubmission($submissionData) {
	$con = connectToDatabase();
	$today = date("Y-m-d");
	mysqli_query($con, "Insert into submission (id_account, id_medium, title, date_submitted, filename, extension)
			VALUES ('" . $submissionData['id_account'] . "', '" .
						$submissionData['id_medium'] . "', '" .
						$submissionData['title'] . "', '" .
						$today . "', '" .
						$submissionData['filename'] . "', '" .
						$submissionData['extension'] . "')");
	
}

function deleteSubmission($submissionID) {
	
}

function insertSubmissionGenre($submissionID, $genreID) {
	
}

function deleteSubmissionGenre($submissionID, $genreID) {
	
}

function insertSubmissionTag($submissionID, $tag) {
	
}

function deleteSubmissionTag($submissionID, $tag) {
	
}

function insertTag($tag) {
	
}

function selectSubmission($submissionID) {
	
}

function search($tags) {
	
}

function selectPopularWorks($mediumType) {
	
}


?>