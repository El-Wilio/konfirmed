<?php 
include_once('../code/backbone.php');
	echo "<h2>Submission that should be spotlight:</h2>";
	$submission = selectSubmission(63);
	if($submission['isSpotlight']) {
		echo "Yes<br />";
	} else {
		echo "No<br />";
	}
	echo "<h2>Submission that should not be in spotlight:</h2>";
	$submission = selectSubmission(5);
	if($submission['isSpotlight']) {
		echo "Yes<br />";
	} else {
		echo "No<br />";
	}
	echo "<h2>Artist that should be in spotlight:</h2>";
	$account = selectProfile(3);
	if($account['isSpotlight']) {
		echo "Yes<br />";
	} else {
		echo "No<br />";
	}
	echo "<h2>Artist that should not be in spotlight:</h2>";
	$account = selectProfile(2);
	if($account['isSpotlight']) {
		echo "Yes<br />";
	} else {
		echo "No<br />";
	}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Konfirmed</title>
        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <meta class="viewport" name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="scripts/redirect.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
        <script src="scripts/noscroll.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
        <script src="scripts/login-register.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
        <script src="https://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
        <link rel="stylesheet" href="stylesheets/style.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
        <link rel="stylesheet" href="stylesheets/fonts.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
    </head>
    <body>
        <div class="navigation-background"></div>
        <div class="navigation">
            <ul class="inline right">
                <li><a href="#">About</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <? if(isLoggedIn()) {
                echo '<li><a href="logout.php">Logout</a></li>';
                } ?>
            </ul>
        </div>
        <div class="menu">
            <div class="logo"></div>
            <ul class="menu-items inline center">
                <li><div class="music box"></div></li><!--
                --><li class="second-row"><div class="art box"></div></li><!--
                --><span class="seperator"></span><!--
                --><li class="second-row"><div class="photography box"></div></li><!--
                --><li><div class="writer box"></div></li>
                <? if(!isLoggedIn()) {
                    echo '<li>';            
                    echo '<div class="login-register">';
                    echo '<button class="login">Login</button><!--';
                    echo '--><button class="register">Register</button>';
                    echo '</div>';
                    echo '</li>'.$_SESSION['LoggedInAs'];
                } ?>
            </ul>
        </div>
       
        <div class="darkfilter"></div>
        <div class="register-box"></div>
        <div class="login-box"></div>
        <div class="selected-category">
          <a href="#"><img src="images/x-icon.png" class="close-selected-category" width="20px" height="auto"></a>
          <ul class="category-table top-genre">
            <li>Genre</li>
          </ul><!--
          --><ul class="category-table">
            <li>Popular Artists</li>
            <li>William Wright</li>
            <li>Amine Khaite</li>
            <li>Ho </li>
            <li>THE GREATEST STUFF</li>
            <li>THE GREATEST STUFF</li>
          </ul><!--
          --><ul class="category-table">
            <li>Popular Works</li>
          </ul>
        </div>
    </body>
    
</html>