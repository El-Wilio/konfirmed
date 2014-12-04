<?php 
include_once('../code/backbone.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Konfirmed</title>
        <script src="http://connect.soundcloud.com/sdk.js"></script>
        <script>
          SC.initialize({
            client_id: "f10232de97859df1da5abb9d3965b225"
          });
        </script>
        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <meta class="viewport" name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="scripts/redirect.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
        <script src="scripts/noscroll.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
        <script src="https://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
        <link rel="stylesheet" href="stylesheets/style.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
        <link rel="stylesheet" href="scripts/scroller/jquery.mCustomScrollbar.css" />
        <script src="scripts/scroller/jquery.mCustomScrollbar.concat.min.js"></script>
    </head>
    <body>
        <div class="menu">
            <div class="logo"></div>
            <ul class="menu-items inline center">
                <li class="second-row"><div class="image art box"></div></li><!--
                --><li class="second-row"><div class="video photography box"></div></li><!--
                 --><span class="seperator"></span><!--
                --><li><div class="text writer box"></div></li><!--
                --><li><div class="audio music box"></div></li>
                <? if(!isLoggedIn()) {
                    echo '<laui>';            
                    echo '<div class="login-register">';
                    echo '<button class="login">Login</button><!--';
                    echo '--><button class="register">Register</button>';
                    echo '</div>';
                    echo '</li>'.$_SESSION['LoggedInAs'];
                } ?>
            </ul>
        </div>
        <?php include('topnav.php'); ?>
        <div class="darkfilter"></div>
        <div class="register-box"></div>
        <div class="login-box"></div>
        <div class="selected-category">
          <a href="#"><img src="images/x-icon2.png" class="close-selected-category" width="20px" height="auto"></a>
        </div>
     </div>
      <div class="left-sidebar">
     <?php include('left-sidebar.php'); ?>
     </div>
    <link rel="stylesheet" href="scripts/perfect-scrollbar-0.5.7/min/perfect-scrollbar.min.css" />
    <script src="scripts/perfect-scrollbar-0.5.7/min/perfect-scrollbar.min.js"></script>
    <script src="scripts/login-register.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    <script src="scripts/spotlightArtist.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    <script src="scripts/imagesloaded.pkgd.min.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    <script src="scripts/masonry.pkgd.min.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    <script src="scripts/profile.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    </body>
    
</html>