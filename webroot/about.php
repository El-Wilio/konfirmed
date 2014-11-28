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
        <h2 class="text">Mission</h2>
        <p class="text">
        Our mission is to provide a platform to showcase the works of various talented-minds, pursuing common goals
        and connect them with other like-minded creative professionals, clients, and art aficionados around the globe. 
        Through sponsored events and our growing affiliates we offer a wider audience to members of our Community.
        </p>
        <h2 class="text">Description</h2>
        <p class="text">
        Konfirmed is a coalescence of the Community Network, Media Group and Record Label, 
        with each one of the above serving itÅfs owns specific purpose. Konfirmed is a professional network 
        geared towards the promotion and publication of art; whether vocal/musical, couture, sculpting, painting, graphics
        and interactive media design, all ingenious genres and mediums are encouraged
        </p>
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