<?php include_once('../code/backbone.php');

if(isLoggedIn()) {
  $profileInfo = selectCurrentProfile();
  $status = "self_profile";
}
else {
  $status = "error";  
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Konfirmed</title>
        <meta charset="UTF-8">
        <meta class="viewport" name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="stylesheets/style2.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="http://connect.soundcloud.com/sdk.js"></script>
        <script src="scripts/scroller/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="scripts/redirect.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    </head>
    <body>
      <div class="wrapper">
        <div class="content-single">
            <?  

            if(isset($_GET['id'])) {
              
              $submission = selectSubmission($_GET['id']);
              $page = 'submission';
              $whoIs = selectProfile($submission['id_account']);
              ?>
        <div class="content-wrapper single-submission" data-id="<?php echo $submission['id']; ?>">
          <? if($submission['medium'] == 'image') {
            ?>
          <div class="content-presentation single-picture"
            style="background: url('submissions/image/<?php echo $submission['filename'].".".$submission['extension']; ?>') center center no-repeat; background-color: #bf1e2e;">
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
          <? } ?>
          <h1 class="clickable"><a href="submission.php?id=<?php echo $submission['id']; ?>"><? echo $submission['title']; ?></a></h1>
          <p class="content-date">On <?php echo interpretDate($submission['date_submitted']); ?></p>
       </div>
            <? }
            
            else if(isset($_GET['new'])) {
            ?>
                <div class="input-box">
                <input type="text" name="title" class="submission-input " 
                    placeholder="Title" autocomplete="off">
                </div>
                <div class="input-box the-stuff center">
                    <p style="text-align center;">Please choose an image:<br> <input type="file" name="uploadFile" id="uploader"></p>
                </div>
                <div class="input-box">
                <input type="text" name="tags" class="submission-input" 
                    placeholder="tags (seperate them by commas (,)" autocomplete="off">
                </div>   
                <div class="input-box medium">
                  <select name="medium" class="submission-input"> 
                    <option value="1"><?php echo selectMedium(1) ?></option>
                    <option value="2"><?php echo selectMedium(2) ?></option>
                    <option value="3"><?php echo selectMedium(3) ?></option>
                    <option value="4"><?php echo selectMedium(4) ?></option>
                  </select>
                </div>
                <div class="input-box genres-box">
                    <?php
                      $genres = selectMediumGenres(1);
                      foreach($genres as $genre) { ?>
                      <p class="submission-checkbox"><input name="genre" type="checkbox" value="<?php echo $genre['id'] ?>"><?php echo $genre['genre']; } ?></p>
                </div>

                <div class="button-field"><button class="send-information">Submit</button></div>
            <? }

            else if(isset($_GET['search'])) {

            }

            else if(isset($_GET['edit'])) {
              
              $submissions = selectAccountSubmissions(selectLoggedInAccountID());
              
              echo "<div class=\"submission-list\">";
              
              foreach($submissions as $submission) { ?>
              
              <p class="submission-list"><a href="submission.php?view=<?php echo $submission['id']; ?>"><?php echo $submission['title']; ?></a>
              <a href = "#" class="delete" data-submission-id="<?php echo $submission['id']; ?>">Remove submission</a>
              </p>

             <? }
            echo "</div>";
            }

            ?>
        </div>
        </div>
     <? if ($status != "error") { ?>
     <div class="right-sidebar">
        <div class="profile-picture">
          <img class="profile-picture" src=<?php echo "images/profile/".$profileInfo[6] ?> >
          <p class="profile-name"><?php echo $profileInfo[4]." ".$profileInfo[5] ?></p>
        </div>
        <div class="profile-info" data-account-id="<?php echo $profileInfo[1] ?>"><h1>About</h1>
          <p class="profile-info location"><? echo $profileInfo[7] ?></p>
          <p class="profile-info birthday"><? echo $profileInfo[9] ?></p>
          <p class="profile-info artist"><? echo $profileInfo[11] ?></p>
        </div>
        <div class="profile-info" data-account-id="<?php echo $profileInfo[1] ?>">
            <h1>Recently added</h1>
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
    <script src="scripts/profile.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    <script src="scripts/login-register.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    <script src="scripts/submission.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    </body>
</html>