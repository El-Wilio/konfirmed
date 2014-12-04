<?php include_once('../code/backbone.php');
if(isset($_GET['id'])) {
  $page = 'profile';
  $profileInfo = selectProfile($_GET['id']);
  if(count($profileInfo) == 1) $status = "error";
  else $status = "other_profile";
}
else {
  if(isLoggedIn()) {
  $page = 'profile';
  $profileInfo = selectCurrentProfile();
  $status = "self_profile";
  }
  else {
    $status = "error";  
  }
}
if($status == "other_profile" || $status =="self_profile") {
    $submissions = selectGroupSubmission($profileInfo[1], 0, 3);
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Konfirmed</title>
        <link rel="stylesheet" href="stylesheets/style2.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="http://connect.soundcloud.com/sdk.js"></script>
        <script>
          SC.initialize({
            client_id: "f10232de97859df1da5abb9d3965b225"
          });
        </script>
        <meta charset="UTF-8">
        <meta class="viewport" name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="scripts/redirect.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
        <script src="https://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
        <script src="scripts/scroller/jquery.mCustomScrollbar.concat.min.js"></script>
    </head>
    <body>
      <div class="wrapper">
      <? if ($status != "error") { ?>
      <div class="banner">
      <div class="cover"></div>
      <ul class="banner-command">
        <li><a href="#">Timeline</a></li>
        <li><a href="#">About</a></li>
      </ul>
      </div>
      <? } ?>
      <div class="content"> 
      <?
       $i = 0;
       if(isset($submissions)) {
       foreach($submissions as $submission) { 
       if ($i == 0) {
        echo '<div class="column-1">';       
       }
       else {
        echo '<div class="column-2">'; 
       }
       ?>
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
           <iframe width="100%" height="345px" src="//www.youtube.com/embed/<?php echo $youtubeID; ?>" frameborder="0" allowfullscreen></iframe>
          </div>
          <? } ?>
          <? if($submission['medium'] == 'audio') {
            $track_url = $submission['filename'];
            ?>
          <div class="content-presentation content-audio-<?php echo $submission['id']; ?> audio2">
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
          </div>
          
          <?
          $i += 1;
          if($i == 2) {
            $i = 0;   
          } 
          } ?>
          <h1 class="clickable"><a href="submission.php?id=<?php echo $submission['id']; ?>"><? echo $submission['title']; ?></a></h1>
          <p class="content-date">On <?php echo interpretDate($submission['date_submitted']); ?></p>
       </div>
       <? } }?>
      </div>
      </div>
     <? if ($status != "error") { ?>
     <div class="right-sidebar">
        <div class="profile-picture">
          <img class="profile-picture" src=<?php echo "images/profile/".$profileInfo[6] ?> >
          <p class="profile-name"><?php echo $profileInfo[4]." ".$profileInfo[5] ?></p>
        </div>
        <div class="profile-info" data-account-id="<?php echo $profileInfo[1] ?>"><p class="title">About</p>
          <p class="profile-info location"><? echo $profileInfo[7] ?></p>
          <p class="profile-info birthday"><? echo $profileInfo[9] ?></p>
          <p class="profile-info artist"><? echo $profileInfo[11] ?></p>
        </div>
        <div class="profile-info" data-account-id="<?php echo $profileInfo[1] ?>">
            <p class="title">Recently added</p>
            <?php
            $lasts = selectLastFiveSubmissions($profileInfo[1]);
            foreach($lasts as $last) { ?>
            <p class="profile-info submission-list"><a href="submission.php?id=<?php echo $last['id']; ?>"><?php echo $last['title']; ?></a></p>
            <? } ?>
        </div>
      </div>
       <? } ?>
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
    <script src="scripts/mousewheel.js"></script>
    <link rel="stylesheet" href="scripts/scroller/jquery.mCustomScrollbar.css" />
    <script src="scripts/login-register.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    <script src="scripts/spotlightArtist.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    <script src="scripts/profile.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    </body>
</html>