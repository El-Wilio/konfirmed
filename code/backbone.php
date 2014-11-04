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

function insertRating($submissionID, $accountID, $rating) {
	$con = connectToDatabase();
	
	$result = mysqli_query($con, "Select * from rating where id_account = 99 and id_submission = " . $submissionID);
	
	if($row = mysqli_fetch_array($result)) {
		$retval = false;
	} else { 
		mysqli_query($con, "Insert into rating (id_submission, id_account, rating)
					VALUES ('" . $submissionID . "', '" . $accountID . "', '" . $rating . "')");
		$retval = -1;
		if(mysqli_affected_rows($con) == -1) {
			$retval = false;
		} else if(mysqli_affected_rows($con) == 1) {
			$retval = true;
		} else { 
			$retval = -1;
		}
	}
	mysqli_close($con);
	return $retval;
	
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
	echo "submissionData: <br />";
	print_r($submissionData);
	mysqli_query($con, "Insert into submission (id_account, id_medium, title, date_submitted, filename, extension)
			VALUES ('" . $submissionData['id_account'] . "', '" .
						$submissionData['id_medium'] . "', '" .
						$submissionData['title'] . "', '" .
						$today . "', '" .
						$submissionData['filename'] . "', '" .
						$submissionData['extension'] . "')");
						
						
	$submissionID = $con->insert_id;
	foreach($submissionData['genres'] as $genre) {
		mysqli_query($con, "Insert into  submission_genre (id_submission, id_genre)
							VALUES ('" . $submissionID . "', '" . $genre . "')");
	}
	
	echo "<h2>Tags: </h2>";
	print_r($submissionData['tags']);
	
	foreach($submissionData['tags'] as $tag) {
		$tag = transformTagIntoTagID($tag);
		mysqli_query($con, "Insert into submission_tag (id_submission, id_tag)
							VALUES ('" . $submissionID . "', '" . $tag . "')");	
	}
	mysqli_close($con);
}

//$add is boolean, true by default
// true => if the tag doesn't exist, add it
// false => just get the id, return -1  if it doesn't exist
function transformTagIntoTagID($tag, $add = true) {
	$tag = strtolower($tag);
	$con = connectToDatabase();
	$result = mysqli_query($con, "Select id from tag where name = '" . $tag . "'");
	$row = mysqli_fetch_array($result);
	if(isset($row['id'])) { 
		mysqli_close($con);
		return $row['id']; 
	} else {
		if($add) {
			mysqli_query($con, "Insert into tag (name)
								VALUES ('" . $tag . "')");
			$id = $con->insert_id;
		} else {
			$id = -1;
		}
		mysqli_close($con);
		return $id;
	}
}


//This function checks to see if there is already a submission with the same name. If so, it appends a number
//At the end to prevent having doubles. 
function fixFilename($filename, $extension, $mediumID) {
	$con = connectToDatabase();
	$result = mysqli_query($con, "Select filename, extension from submission where id_medium = " . $mediumID);
	$fullFilename = array();
	while($row = mysqli_fetch_array($result)) {
		array_push($fullFilename, ($row['filename'] . "." . $row['extension']));
	}
	
	$append = 1;
	while(in_array((strtolower($filename . $append . "." . $extension)), $fullFilename)) {
		$append++;
	}
	$filename .= $append;// . "." . $extension;
	mysqli_close($con);
	return $filename;
}

function deleteSubmission($submissionID) {
  $message = '';
	$con = connectToDatabase();
	mysqli_query($con, "Delete from submission where id = " . $submissionID);
	if(mysqli_affected_rows($con) == 1) $message = 'success';
	else $message = 'error';
	mysqli_query($con, "Delete from submission_genre where id_submission = " . $submissionID);
	mysqli_query($con, "Delete from submission_tag where id_submission = " . $submissionID);
	mysqli_query($con, "Delete from rating where id_submission = " . $submissionID);
	mysqli_close($con);
	return $message;
}

/*The following are accessible from the return value of this function, let it be $submission: 
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
	$submission['rating'] (returns a decimal from 0 to 5)*/
function selectSubmission($submissionID) {
	$con = connectToDatabase();
	//1. Get all submissions 
	$result = mysqli_query($con, "Select * from submission where id = " . $submissionID);
	//2. Put each one into the array that is to be returned to the front end
	$submission = mysqli_fetch_array($result);
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
	mysqli_close($con);
	return $submission;
}

function selectGroupSubmission($accountID, $start, $repetition) {
  $con = connectToDatabase();
	//1. Get all submissions 
	$result = mysqli_query($con, "Select * from submission where id > " . $start . " AND id_account = " . $accountID . " LIMIT ". $repetition);
	$submissions = array();
	while($submission = mysqli_fetch_array($result)) {
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

function selectLoggedInAccountID() {
	$username = $_SESSION['LoggedInAs'];
	$con = connectToDatabase();
	$result = mysqli_query($con, "Select id from account where username = '" . $username . "'");
	$row = mysqli_fetch_array($result);
	return $row['id'];	
} 

function search($tags) {
	$tags = explode(" ", $tags);
	//Transform the tags into tagIDs
	$con = connectToDatabase(); 
	$tagIDs = array();
	foreach($tags as $tag) {
		array_push($tagIDs, transformTagIntoTagID($tag, false));
	}
	//Get all the submission_ids from submission_tag that have a matching tagID 
	$submissionIDs = array();
	$query = "Select distinct(id_submission) from submission_tag where id_tag in (";
	foreach($tagIDs as $tagID) {
		$query .= $tagID;
		if($tagIDs[count($tagIDs)-1] != $tagID) {
			$query .= ", ";
		}
	}
	$query .= ")";
	$result = mysqli_query($con, $query);
	
	while($row = mysqli_fetch_array($result)) {
		array_push($submissionIDs, $row['id_submission']);
	}
	//For each submission id, count the number of rows that have a matching tag
	$searchResultIDs = array();
	foreach($submissionIDs as $submissionID) {
		$query = "Select count(id) as count " .
					"from submission_tag " .
					"where id_submission = " . $submissionID .
					" and id_tag in (";
		foreach($tagIDs as $tagID) {
			$query .= $tagID;
			if($tagIDs[count($tagIDs)-1] != $tagID) {
				$query .= ", ";
			}
		}
		$query .= ")";
		
		$result = mysqli_query($con, $query);
		$row = mysqli_fetch_array($result);
		//If they have as many matching rows as count($tags), add it to the search results
		if($row['count'] == count($tagIDs)) {
			array_push($searchResultIDs, $submissionID);
		}
	}
	//For each search result, select the submission by submission ID and add it to an array
	$submissions = array();
	foreach($searchResultIDs as $submissionID) {
		 $submission = selectSubmission($submissionID);
		 array_push($submissions, $submission);
	}
	//return the array
	return $submissions;
}


/*
	Returns an array of the top 10 most popular submissions of the passed mediumID. 
	If we do foreach($arr as $submission), we have the following data available: 
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
	$submission['numRatings'] (returns the amount of ratings that submission has received)
	
*/
function selectPopularSubmissions($idMedium, $limitToTen = true) {
	$con = connectToDatabase();
	//Get all the submissions with this medium. 
	$result = mysqli_query($con, "Select distinct(id) from submission where id_medium = " . $idMedium);
	$idSubmissions = array();
	while($row = mysqli_fetch_array($result)) {
		array_push($idSubmissions, $row['id']);
	}
	//Get all the submissions from the previous list that are also in rating
	$query = "Select distinct(id_submission) as id from rating where id_submission in(";
	foreach($idSubmissions as $idSubmission) {
		$query .= $idSubmission;
		if($idSubmissions[count($idSubmissions)-1] != $idSubmission) {
			$query .= ", ";
		}
	}
	$query .= ")";
	$result = mysqli_query($con, $query);
	$idSubmissions = array();
	while($row = mysqli_fetch_array($result)) {
		array_push($idSubmissions, $row['id']);
	}
	//For each submission in that list, count how many ratings they have. 
	$submissionIDsWithRatings  = array();
	$submissionIDsWithRatings[0] = array();
	$submissionIDsWithRatings[1] = array();
	
	foreach($idSubmissions as $idSubmission) {
		$result = mysqli_query($con, "select count(id) as count from rating where id_submission = " . $idSubmission);
		$row = mysqli_fetch_array($result);
		$count = $row['count'];
		if($count > 0) {
			array_push($submissionIDsWithRatings[0], $idSubmission);
			array_push($submissionIDsWithRatings[1], $count);
		}
	}
	
	//Sort them in descending order
	array_multisort($submissionIDsWithRatings[1], SORT_DESC, $submissionIDsWithRatings[0]);
	
	//return the top ten
	$sub = array();
	if($limitToTen) {
		$sub["id"] = array_slice($submissionIDsWithRatings[0], 0, 10);
		$sub["count"] = array_slice($submissionIDsWithRatings[1], 0, 10);
	} else {
		$sub["id"] = $submissionIDsWithRatings[0];
		$sub["count"] = $submissionIDsWithRatings[1];
	}
	
	//turn this into an array of the top ten most popular submissions with this medium. 
	$submissions = array();
	for($x = 0; $x < count($sub["id"]); $x++) {
		$submission = selectSubmission($sub["id"][$x]);
		$submission["numRatings"] = $sub["count"][$x];
		array_push($submissions, $submission);
	}
	mysqli_close($con);
	return $submissions;
}

/*
	
*/
function selectPopularArtists($idMedium) {
	$popularWorks = selectPopularSubmissions($idMedium, false);
	
	$popularArtists = array();
	$popularArtists["idAccount"] = array();
	$popularArtists["numRatings"] = array();
	
	$popularArtists["idAccount"][0] = 1;
	$popularArtists["numRatings"][0] = 4;
	
	foreach($popularWorks as $work) {
		if(in_array($work['id_account'], $popularArtists["idAccount"])) {
			$index = array_search($work['id_account'], $popularArtists["idAccount"]);
			$popularArtists["numRatings"][$index] += $work["numRatings"];
		} else {
			array_push($popularArtists["idAccount"], $work["id_account"]);
			$index = array_search($work["id_account"], $popularArtists["idAccount"]);
			$popularArtists["idAccount"][$index] = $work["numRatings"];
		}
	}
	
	for($x = 0; $x < count($popularArtists["idAccount"]); $x++) {
		echo "ID: " . $popularArtists["idAccount"][$x] . "   Count: " . $popularArtists["numRatings"][$x] . "<br />";
	}
	
	
}

/* Returns array $genres
	When you foreach($genres as $genre), you have: 
		$genre['id']
		$genre['genre']
*/
function selectMediumGenres($idMedium) {
	$con = connectToDatabase();
	$result = mysqli_query($con, "Select id, name from genre where id_medium = " . $idMedium);
	$genres = array();
	while($row = mysqli_fetch_array($result)) {
		$genre = array("id" => $row['id'], "genre" =>$row['name']);
		array_push($genres, $genre);
	}
	mysqli_close($con);
	return $genres;
}

function searchByGenre($idGenre) {
	$con = connectToDatabase();
	$result = mysqli_query($con, "Select id_submission from submission_genre where id_genre = " . $idGenre);
	$submissionIDs = array();
	while($row = mysqli_fetch_array($result)) {
		array_push($submissionIDs, $row['id_submission']);
	}
	$submissions = array();
	foreach($submissionIDs as $submissionID) {
		$submission = selectSubmission($submissionID);
		array_push($submissions, $submission);
	}
	mysqli_close($con);
	return $submissions;
}

function selectSpotlightArtists() {
	$con = connectToDatabase();
	$arr = array();
	
	$result = mysqli_query($con, "Select id_account from spotlight");
	while($row = mysqli_fetch_array($result)) {
		array_push($arr, selectProfile($row['id_account']));
	}
	
	mysqli_close($con);
	return $arr;
}

function selectSpotlightSubmissions() {
	$con = connectToDatabase();
	$arr = array();
	
	$result = mysqli_query($con, "Select id_submission from spotlight");
	while($row = mysqli_fetch_array($result)) {
		array_push($arr, selectSubmission($row['id_submission']));
	}
	
	mysqli_close($con);
	return $arr;
}

function insertSpotlightArtist($idAccount) {
	$con = connectToDatabase();
	
	
	mysqli_close($con);
}

function insertSpotlightSubmission($idSubmission) {
	$con = connectToDatabase();
	
	
	mysqli_close($con);
}

function deleteSpotlightArtist($idAccount) {
	$con = connectToDatabase();
	
	
	mysqli_close($con);
}

function deleteSpotlightSubmission($idSubmission) {
	$con = connectToDatabase();
	
	
	mysqli_close($con);
}


?>