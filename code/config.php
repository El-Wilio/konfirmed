<?

function connectToDatabase() {
		$con = mysqli_connect("konfirmedcom.fatcowmysql.com", "cbarrieau", "K0nfirmed12.", "db_konfirmed");
		if(mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();	
		}
		return $con;
	}
    
?>