<?php
//Include classes
$classes = scandir("../code/classes");
foreach($classes as $filename) {
	if($filename != "." && $filename != "..") {
		include("../code/classes/" . $filename);	
	}
}

<<<<<<< HEAD
=======
//Temporary code
function connectToDatabase() {
	$con = mysqli_connect("konfirmedcom.fatcowmysql.com", "cbarrieau", "K0nfirmed12.", "db_konfirmed");
	if(mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();	
	}
	//$result = mysqli_query($con, "Select * From profile");
	//while($row = mysqli_fetch_array($result)) {
	//	echo $row['id'] . " - " . $row['name'];
	//	echo "<br />";
	//}
	//mysqli_close($con);
	return $con;
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
>>>>>>> origin/chris


?>