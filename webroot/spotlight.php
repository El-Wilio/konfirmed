<? session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <?php include_once('../code/backbone.php'); ?>
        <title>Konfirmed</title>
        <script src="http://connect.soundcloud.com/sdk.js"></script>
        <script>
          SC.initialize({
            client_id: "f10232de97859df1da5abb9d3965b225"
          });
        </script>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,700,300,600' rel='stylesheet' type='text/css'>
        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <meta class="viewport" name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="scripts/scroller/jquery.mCustomScrollbar.css" />
        <script src="scripts/scroller/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="scripts/noscroll.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
        <script src="https://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
        <link rel="stylesheet" href="stylesheets/style2.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
    </head>
    <body>
        <div class="wrapper wrapper-single">      
        <div class="content-single">
            <a class="logo" href="/temp/webroot"></a>
            <div class="left-box">
            <?php $artists = selectSpotlightArtists(); 
            echo "<span>";
            foreach($artists as $artist) { ?>
                <a href="profile.php?id=<?php echo $artist[1]; ?>"><img src="images/profile/<?php echo $artist[6]; ?>" class="profile-picture" /></a>
           <? } ?>
            </span>
            </div>
            <div class="right-box">
              <?php $submissions = selectSpotlightSubmissions();
              
                  foreach($submissions as $submission) { ?>
                    <div class="content-wrapper" data-id="<?php echo $submission['id']; ?>">
                      <? if($submission['medium'] == 'image') {
                        ?>
                      <div class="content-presentation picture"
                        style="background: url('submissions/image/<?php echo $submission['filename'].".".$submission['extension']; ?>') center center no-repeat;">
                      </div>
                      <? } ?>
                      <? if($submission['medium'] == 'text') {
                        ?>
                      <div class="content-presentation text-box">
                        <?
                          $handle = fopen('submissions/'.$submission['medium'].'/'.$submission['filename'].'.txt', "r");
                         if ($handle) {
                            while (($line = fgets($handle)) !== false) {
                                echo $line."<br>";
                            }
                        } else {
                            // error opening the file.
                        } 
                        fclose($handle); ?>
                        </div>
                      <? } ?>
                      <? if($submission['medium'] == 'video') {
                        $youtubeID = end(explode("/", $submission['filename']));
                      ?>
                      <div class="content-presentation video2">
                       <iframe width="100%" height="350px" src="//www.youtube.com/embed/<?php echo $youtubeID; ?>" frameborder="0" allowfullscreen></iframe>
                      </div>
                      <? } ?>
                      <? if($submission['medium'] == 'audio') {
                        $track_url = $submission['filename'];
                        ?>
                      <div class="content-presentation content-audio-<?php echo $submission['id']; ?>">
                            <script>
                                $(function() {
                                var track_url = '<?php echo $track_url; ?>';
                                var real_url ="test";
                                SC.initialize({
                                    client_id: "f10232de97859df1da5abb9d3965b225"
                                  });
                                SC.get('/resolve', { url: track_url }, function(track) {
                                  SC.get('/tracks/' + track.id + '/comments', function(comments) {
                                    console.log(track.id);
                                    real_url = "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/"+track.id+"&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true";
                                    $('.content-audio-<?php echo $submission['id']; ?>').html('<iframe width="100%" height="450" scrolling="no" frameborder="no" src="'+real_url+'"></iframe>');
                                    for (var i = 0; i < comments.length; i++) {
                                    }
                                  });
                                });
                                });
                            </script>
                      </div>
                      <? } ?>
                    <h1 class="clickable"><a href="submission.php?id=<?php echo $submission['id']; ?>"><? echo $submission['title']; ?></a></h1>
                    <p class="content-date">On <?php echo interpretDate($submission['date_submitted']); ?></p>
                   </div>
                   <? } ?>
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
        <script src="scripts/profile.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
        <script src="scripts/login-register.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    <?php include('topnav.php'); ?>
    </body>
    
</html>