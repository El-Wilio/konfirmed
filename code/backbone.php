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

function isLoggedInAsAdmin() {
	return ($_SESSION['LoggedInAs'] == "admin@konfirmed.com");
}

function isProfileEmpty() {
	if (isset($_SESSION['LoggedInAs'])) {
		$con = connectToDatabase();
		$result = mysqli_query($con, "Select id from account where username = '" . $_SESSION['LoggedInAs'] . "'");
		$row = mysqli_fetch_array($result);
		
		$result = mysqli_query($con, "Select count(id) as 'HasProfile' From profile where id_account = '" . $row['id'] . "'");
		//$result = mysqli_query($con, "Select count(id) as 'HasProfile' From profile where id_account = '123456789'");
		$row = mysqli_fetch_array($result);
		$isProfileEmpty = true;
		if($row['HasProfile'] > 0) {
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
	
	//Perform spotlight check
	$isSpotlight = false;
	$result = mysqli_query($con, "Select count(id) as count from spotlight where id_account = " . $accountID);
	$row = mysqli_fetch_array($result);
	if($row['count'] > 0) {
		$isSpotlight = true;
	}
	
	$profile['isSpotlight'] = $isSpotlight;
	
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

function selectLastFiveSubmissions($accountID) {
	$con = connectToDatabase();
	//1. Get all submissions 
	$result = mysqli_query($con, "Select * from submission where id_account = '" . $accountID . "' order by date_submitted desc LIMIT 5");
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
    if ($avgRating == null) return "no rating";
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
	mysqli_query($con, "Delete from rating where id_submission = " . $submissionID . " and id_account = " . $accountID);
	$result = mysqli_query($con, "Select * from rating where id_account = " . $accountID . " and id_submission = " . $submissionID);
	
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
	$submissionData['text'] (Optional, only needed for text based submissions
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
						
	
	//If it's a text file, take the text and put it in a text file. 					
	if($submissionData['id_medium'] == 3) {	
		makeTextFile($submissionData['filename'], $submissionData['extension'], $submissionData['text']);
	}
	
	$submissionID = $con->insert_id;
	
	foreach($submissionData['genres'] as $genre) {
		mysqli_query($con, "Insert into  submission_genre (id_submission, id_genre)
							VALUES ('" . $submissionID . "', '" . $genre . "')");
	}
	
	foreach($submissionData['tags'] as $tag) {
		$tag = transformTagIntoTagID($tag);
		mysqli_query($con, "Insert into submission_tag (id_submission, id_tag)
							VALUES ('" . $submissionID . "', '" . $tag . "')");	
	}
    echo $submissionID;
	mysqli_close($con);
}


/*
	Internal file used by insertSubmission only when inserting a text submission
	*/
function makeTextFile($filename, $extension, $text) {
	$myFile = fopen("submissions/text/" . $filename . "." . $extension, "w") or die("Unable to open file!");
	fwrite($myFile, $text);
	fclose($myFile);
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
	
	//Perform spotlight check
	$isSpotlight = false;
	$result = mysqli_query($con, "Select count(id) as count from spotlight where id_submission = " . $submissionID);
	$row = mysqli_fetch_array($result);
	if($row['count'] > 0) {
		$isSpotlight = true;
	}
	
	$submission['isSpotlight'] = $isSpotlight;
	
	mysqli_close($con);
	return $submission;
}

function selectGroupSubmissionMedium($mediumID, $start, $repetition) {
  $con = connectToDatabase();
	//1. Get all submissions 
	$result = mysqli_query($con, "Select * from submission where id > " . $start . " AND id_medium = " . $mediumID . " LIMIT ". $repetition);
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
    $submissions = array();
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
    if(gettype($result) != "boolean") {
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
        for($x = 0; $x < count($sub["id"]); $x++) {
            $submission = selectSubmission($sub["id"][$x]);
            $submission["numRatings"] = $sub["count"][$x];
            array_push($submissions, $submission);
        }
    }
    else {
        $submission = array(false, 0, 0, 0, 0, 'no entry');
        array_push($submissions, $submission);
    }
    mysqli_close($con);
	return $submissions;
}

/*
	Returns the same thing as selectProfile but also has $arrName["numRatings"] at the end.
*/
function selectPopularArtists($idMedium) {
	//Call getPopularSubmissions and don't limit to 10 submissions.
	//Use this data to determine popular Artists.
	$popularWorks = selectPopularSubmissions($idMedium, false);
    $artists = array();
	if ($popularWorks[0][0] != false) {
        //First, go through the Popular Works and get all the artists in there. 
        $popularArtists = array();
        $popularArtists["idAccount"] = array();
        $popularArtists["numRatings"] = array();
        foreach($popularWorks as $work) {
            $idAccount = $work['id_account'];
            if(in_array($idAccount, $popularArtists["idAccount"]) == FALSE) {
                array_push($popularArtists["idAccount"], $idAccount);
            }
        }
	
        //Then go through the popular works again. For each popular work: 
        foreach($popularWorks as $work) {
            //	1. Get the ID and rating
            $idAccount = $work['id_account'];
            $rating = $work['numRatings'];
            //	2. Get the index for the ID, 
            $index = array_search($idAccount, $popularArtists["idAccount"]);
            //	3. Then at the same index for the rating, check to see if there's a rating count
            if(isset($popularArtists["numRatings"][$index])) {
            //		If yes: add the rating
                $popularArtists["numRatings"][$index] += $rating;
            } else {
            //		If no: set the rating
                $popularArtists["numRatings"][$index] = $rating;
            }
        }
        //	4. Sort the artists by numRatings, best to worst
        array_multisort($popularArtists["numRatings"], SORT_DESC, $popularArtists["idAccount"]);
        //	5. Keep the top 10
        array_slice($popularArtists["numRatings"], 0, 10);
        array_slice($popularArtists["idAccount"], 0, 10);
        //	6. Put all that into one nice array to return to the front end
        for($x = 0; $x < count($popularArtists["numRatings"]); $x++) {
            $artist = selectProfile($popularArtists["idAccount"][$x]);
            $artist['numRatings'] = $popularArtists["numRatings"][$x];
            array_push($artists, $artist);
        }
    }
    else {
        $artist = array(false, 0, 0, 0, 'no', 'entry');
        array_push($artists, $artist);
    }
	return $artists;
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
        if($row['id_account'] != '') {
            array_push($arr, selectProfile($row['id_account']));
        }
	}
	
	mysqli_close($con);
	return $arr;
}

function isSpotlightedArtist($id) {
	$con = connectToDatabase();
	
	$result = mysqli_query($con, "Select * FROM spotlight WHERE id_account='$id' LIMIT 1");
    if(mysqli_fetch_row($result)) $message = 'yes';
    else $message = 'no';
	mysqli_close($con);
	return $message;
}

function isSpotlightedSubmission($id) {
	$con = connectToDatabase();

	$result = mysqli_query($con, "Select * FROM spotlight WHERE id_submission='$id' LIMIT 1");
    if(mysqli_fetch_row($result)) $message = 'yes';
    else $message = 'no';
	mysqli_close($con);
	return $message;
}

function selectSpotlightSubmissions() {
	$con = connectToDatabase();
	$arr = array();
	
	$result = mysqli_query($con, "Select id_submission from spotlight");
	while($row = mysqli_fetch_array($result)) {
        if($row['id_submission'] != '') {
		array_push($arr, selectSubmission($row['id_submission']));
        }
	}
	
	mysqli_close($con);
	return $arr;
}

function selectSpotlightSubmissionsByMedium($mediumID) {
	$con = connectToDatabase();
	$arr = array();
    $submissions = array();
	
	$result = mysqli_query($con, "Select id_submission from spotlight");
	while($row = mysqli_fetch_array($result)) {
        if($row['id_submission'] != '') {
		array_push($arr, $row['id_submission']);
        }
	}
	
    foreach($arr as $value) {
        $result = mysqli_query($con, "Select id_medium FROM submission WHERE id='$value' LIMIT 1");
        while ($row = mysqli_fetch_row($result)) {
            if($row[0] == $mediumID) {
                array_push($submissions, selectSubmission($value));
            }
        }
    }
    
	mysqli_close($con);
	return $submissions;

}

function insertSpotlightArtist($idAccount) {
	$con = connectToDatabase();
	$query = "Insert into spotlight (id_account)
				VALUES('" . $idAccount . "')";
	mysqli_query($con, $query);
	
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

function insertSpotlightSubmission($idSubmission) {
	$con = connectToDatabase();
	$query = "Insert into spotlight (id_submission)
				VALUES('" . $idSubmission . "')";
	mysqli_query($con, $query);
	
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

function deleteSpotlightArtist($idAccount) {
	$con = connectToDatabase();
	$query = "Delete from spotlight where id_account = " . $idAccount;
	mysqli_query($con, $query);
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

function deleteSpotlightSubmission($idSubmission) {
	$con = connectToDatabase();
	$query = "Delete from spotlight where id_submission = " . $idSubmission;
	mysqli_query($con, $query);
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

function toStar($rating) {

    $width = ($rating / 5.0) * 68.0;
    
    if($submission['rating'] == "no rating") {
                    echo $submission['rating'];
     }
     else {
                
        echo '<div class="star-holder">';
        echo '<div class="star-rating" style="width: '.$width.'px">';
        echo '</div>';
        echo '<div class="star-rating-shadow">';
        echo '</div>';
        echo '</div>';
    }

}

?>