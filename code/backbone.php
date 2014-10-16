<?php

include_once(dirname( __FILE__ ).'/config.php');

//Include classes
$classes = scandir("../code/classes");
foreach($classes as $filename) {
	if($filename != "." && $filename != "..") {
		include("../code/classes/" . $filename);	
	}
}

//Temporary code
function selectStarFromProfile() {
	$con = connectToDatabase();
	$result = mysqli_query($con, "Select * From profile");
	while($row = mysqli_fetch_array($result)) {
		echo $row['id'] . " - " . $row['name'];
		echo "<br />";
	}
	mysqli_close($con);	
}

//if a user is logged in

function isLoggedIn() {
    if(isset($_SESSION['LoggedInAs'])) return true;
    else return false;
}

//Select
function selectProfile() {
	
}

function selectSubmission() {
	
}

function selectCategories() {
	
}

function selectSubCategories() {
	
}

function selectUsersByCategory() {
	
}

function selectUsersBySubCategory() {
	
}


//Insert
function insertUser() {
	
}

function insertTag() {
	
}

function insertTags() {
	
}

function insertSubmission() {
	
}

function insertCategory() {
	
}

function insertSubCategory() {
	
}


//Update
function updateUser() {
	
}

function updateSubmission() {
	
}

//Delete


?>