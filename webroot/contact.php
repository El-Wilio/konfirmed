<!DOCTYPE html>
<html>
    <head>
        <?php include_once('../code/backbone.php'); ?>
        <title>Konfirmed</title>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,700,300,600' rel='stylesheet' type='text/css'>
        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <meta class="viewport" name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="scripts/noscroll.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
        <script src="scripts/login-register.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
        <script src="https://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
        <link rel="stylesheet" href="stylesheets/style2.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
        
    </head>
    <body>
        <div class="navigation-background"></div>
        <div class="navigation">
            <ul class="inline right">
                <li><a href="#">About</a></li>
                <li><a href="index.php">Home</a></li>
                <? if(isLoggedIn()) {
                echo '<li><a href="logout.php">Logout</a></li>';
                } ?>
            </ul>
        </div>
        <div class="menu">
            <div class="left-content">
                <div class="logo"></div>
                <p class="info">Information about contact here</p>
            </div>
            <div class="right-content">
                <input type="email" name="contact-email" placeholder="your email" class="contact-input">
                <input type="text" name="contact-subject" placeholder="Subject" class="contact-input">
                <textarea name="contact-area" placeholder="Please enter your message" rows="20" class="contact-area"></textarea>
                <input type="submit" name="send message" class="contact-submit">
            </div>
        </div>
        
    </body>
    
</html>