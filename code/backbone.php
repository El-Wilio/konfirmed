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


//CopyPasta Code
function selectStarFromProfile() {
	$con = connectToDatabase();
	$result = mysqli_query($con, "Select * From profile");
	while($row = mysqli_fetch_array($result)) {
		echo $row['id'] . " - " . $row['username'];
		echo "<br />";
	}
	mysqli_close($con);	
}

?>