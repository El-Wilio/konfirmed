<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Testing page</title>
</head>

<body>
<<<<<<< HEAD
<<<<<<< HEAD
<?php
include("../code/backbone.php");
$something = New Something();
$something->talk();
?>
=======
=======
>>>>>>> william
<?php include("../code/backbone.php"); ?>
<h1>Master Back-End Testing Page</h1>
<h2>Intro and information</h2>
<p>This is a one-page-fits-all testing ground for the Konfirmed.com website. All the possible inputs and outputs for the site are on this page and may be tested here. Then, for integration, the function call instructions can easily be given to the front-end developer.</p>
<h3>Login and Register</h3>
<?php
	if(isset($_SESSION['LoggedInAs'])) {
		echo "<h4>Logged in as: " . $_SESSION['LoggedInAs'] . "</h4> - <a href=''>Logout</a>";
	} else {
		echo "<h4>Not logged in.</h4>";
	}
?>
<h4>Register</h4>
	<form action="../code/scripts/register.php" method="post" enctype="multipart/form-data" name="frmRegister">
        <p>
            <label for="txtRegisterUsername">Username: 
                <input type="text" name="txtRegisterUsername" id="txtRegisterUsername" />
            </label>
            <br />
            <label for="passRegisterPassword">Passworod: 
                <input type="password" name="passRegisterPassword" id="passRegisterPassword" />
            </label>
            <br />
            <label for="passRegisterConfirmPassword">Confirm Passworod: 
                <input type="password" name="passRegisterConfirmPassword" id="passRegisterConfirmPassword" />
            </label>
            <br />
            <input type="submit" name="btnRegister" id="btnRegister" value="Register" />
        </p>
    </form>
<h4>Login</h4>
	<form action="../code/scripts/login.php" method="post" enctype="multipart/form-data" name="frmLogin">
        <p>
            <label for="txtLoginUsername">Username: 
                <input type="text" name="txtLoginUsername" id="txtLoginUsername" />
            </label>
            <br />
            <label for="passLoginPass">Password: 
            	<input type="password" name="passLoginPass" id="passLoginPass" />
            </label>
            <br />
            <input type="submit" name="btnLogin" id="btnLogin" value="Login" />
        </p>
    </form>
<h3>Landing Page</h3>
	<?php 
	//Load all categories
	//Foreach category:
		//echo <h4>CategoryName</h4>
		//Foreach subcategory in category
			//echo subcategory
			//foreach artist in subcategory
				//echo artist
	?>
<h3>Profile Page</h3>
	<h4>Profile information</h4>
		<div class="profile">
    		<?php echo "<p>Username</p>"; ?><br />
        	<?php echo "<img src='' alt='profilePicture' />"; ?><br />
			<h5>Submissions</h5>
    	    <?
				//for each submission
					//display submission
						//Allow upvote/downvote
						//Show all tags, in link format
			?>
    	</div>
<h3>Contact Page</h3>
<h4>Send email with AJAX confirm</h4>
<<<<<<< HEAD
>>>>>>> origin/chris
=======
>>>>>>> william
</body>
</html>