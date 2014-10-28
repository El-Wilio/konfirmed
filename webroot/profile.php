<?php include_once('../code/backbone.php'); 
$profileInfo = selectCurrentProfile();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Konfirmed</title>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,700,300,600' rel='stylesheet' type='text/css'>
        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <meta class="viewport" name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="scripts/noscroll.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
        <script src="https://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
        <script src="scripts/profile.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
        <link rel="stylesheet" href="stylesheets/style3.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
    </head>
    <body>
      <div class="content">
        <div class="content-wrapper">
          <h1>INSERT TITLE HERE</h1>
          <div class="content-presentation picture" style="background: url('http://wallpoper.com/images/00/39/04/95/dragons-fantasy_00390495.jpg') center center no-repeat;">
          </div>
          <div class="content-info">
            <div class="content-date">Mars 25th 2014</div>
            <div class="content-rating">5 STARS</div>
          </div>
       </div>
      </div>    
      <div class="top-navbar">
      <ul class="navigation">
        <li><a href="/temp/webroot">Konfirmed</a></li>
        <li class="account">Account
          <ul class="sub-navigation child-of-account">
            <li>Edit Profile</li>
            <li><a href="/temp/webroot/logout.php">Logout</a></li>
          </ul>
        </li>
        <li class="submission">Submissions
          <ul class="sub-navigation child-of-submission">
            <li>Add new submission</li>
            <li>Manage submissions</li>
            <li class="search">Search
              <ul class="sub-navigation child-of-search">
                <li>By categories</li>
                <li>By artists</li>
                <li>By tags</li>
              </ul>
          </ul>
        </li>
        <li class="other">Account
          <ul class="sub-navigation child-of-other">
            <li>About Us</li>
            <li>Contact Us</li>
          </ul>
        </li>
      </ul>
      </div>
     <div class="right-sidebar">
        <div class="profile-picture">
          <img class="profile-picture" src=<?php echo "images/profile/".$profileInfo[6] ?> >
        </div>
        <div class="profile-info">
          <p class="profile-info"><b style="font-size: 16px;">First Name:</b><span class="totheleft"><? echo $profileInfo[4] ?></span></p>
          <p class="profile-info"><b>Last Name:</b><span class="totheleft"><? echo $profileInfo[5] ?></span></p>
          <p class="profile-info"><b>Location:</b><span class="totheleft"><? echo $profileInfo[7] ?></span></p>
          <p class="profile-info"><b>Gender:</b><span class="totheleft"><? echo $profileInfo[8] ?></span></p>
          <p class="profile-info"><b>Birthdate:</b><span class="totheleft"><? echo $profileInfo[9] ?></span></p>
          <p class="profile-info"><b>Bio:</b><span class="totheleft"><? echo $profileInfo[10] ?></span></p>
          <p class="profile-info"><b>Occupation:</b><span class="totheleft"><? echo $profileInfo[11] ?></span></p>
        </div>  
      </div>
    </body>
</html>