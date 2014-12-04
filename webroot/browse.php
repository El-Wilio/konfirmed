<? session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <?php include_once('../code/backbone.php'); ?>
        <title>Konfirmed</title>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,700,300,600' rel='stylesheet' type='text/css'>
        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <meta class="viewport" name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="scripts/noscroll.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
        <script src="https://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
        <link rel="stylesheet" href="stylesheets/style2.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
        
    </head>
    <body>
        <div class="wrapper wrapper-single">
        <div class="content-single">
        <a class="logo" href="/temp/webroot"></a>
        <div class="removable">
        <h1 class="text">Browse</h1>
        <a href="#" class="expand-browse"><h2 class="text2">Visual Art</h2></a>
        <div class="browse-content">
        <?
            $genres = selectMediumGenres(1);
            foreach($genres as $genre) { ?>
            <p class="text"><a href="#" class="browse-it" data-id="<?php echo $genre['id'];?>"><?php echo $genre['genre']; ?></a></p> <? } ?>
        </div>
        <a href="#" class="expand-browse"><h2 class="text2">Video</h2></a>
        <div class="browse-content">
        <?
            $genres = selectMediumGenres(2);
            foreach($genres as $genre) { ?>
            <p class="text"><a href="#" class="browse-it" data-id="<?php echo $genre['id'];?>"><?php echo $genre['genre']; ?></a></p> <? } ?>
        </div>
        <a href="#" class="expand-browse"><h2 class="text2">Writing</h2></a>
        <div class="browse-content">
        <?
            $genres = selectMediumGenres(3);
            foreach($genres as $genre) { ?>
            <p class="text"><a href="#" class="browse-it" data-id="<?php echo $genre['id'];?>"><?php echo $genre['genre']; ?></a></p> <? } ?>
        </div>
        <a href="#" class="expand-browse"><h2 class="text2">Music</h2></a>
        <div class="browse-content">
        <?
            $genres = selectMediumGenres(4);
            foreach($genres as $genre) { ?>
            <p class="text"><a href="#" class="browse-it" data-id="<?php echo $genre['id'];?>"><?php echo $genre['genre']; ?></a></p> <? } ?>
        </div>
        </div>
        </div>
        </div>
        <div class="left-sidebar">
          <?php include('left-sidebar.php'); ?>
        </div>
              <div class="selected-category">
        <a href="#"><img src="images/x-icon.png" class="close-selected-category" width="20px" height="auto"></a>
      </div>
        <div class="darkfilter"></div>
        <div class="register-box"></div>
        <div class="login-box"></div>
    <?php include('topnav.php'); ?>
    <script src="scripts/profile.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    <script src="scripts/login-register.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    </body>
    
</html>