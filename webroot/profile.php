<!DOCTYPE html>
<html>
    <head>
        <title>Konfirmed</title>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,700,300,600' rel='stylesheet' type='text/css'>
        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <meta class="viewport" name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="scripts/noscroll.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
        <script src="https://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
        <link rel="stylesheet" href="stylesheets/style3.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
    </head>
    <body>
      <div class="left-sidebar">
      <div class="search-area">
        <input type="search" name="search-query" class="search-box">
        <input type="submit" class="search-submit" value="search">
      </div>
      <ul class="navigation">
        <li>Home</li>
        <li>Categories</li>
          <ul>
            <li>Music</li>
            <li>Art</li>
            <li>Video</li>
          </ul>
          </ul>
      </ul>
      </div>
      <div class="content">
      <!-- insert php function for content stuff here -->
      </div>
      <div class="right-sidebar">
        <div class="profile-picture">
          <img class="profile-picture" src=<?php echo "inserprofileidpicture.png" ?> >
        </div>
        <div class="profile-info">
          <p class="profile-info"><b>Name:</b> </p>
          <p class="profile-info"><b>Birthday:</b> </p>
          <p class="profile-info"><b>Location:</b> </p>
          <p class="profile-info"><b>Occupation:</b> </p>
          <p class="profile-info"><b>Bio:</b> </p>
        </div>  
      </div>
    </body>
</html>