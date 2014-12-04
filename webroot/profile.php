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

function getLoggedIn() {
    if(isLoggedIn()) {
        $loggedIn = selectCurrentProfile();
        return $loggedIn[1];
    }
    return;
}

function grammarNazi($nazify) {
    if(strlen($nazify) == 0) {
        return "";
    }
    if(preg_match('/^[aeiou]/i', $nazify) == 1) {
        return "is an ".$nazify;
    }
    else {
        return "is a ".$nazify;
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Konfirmed</title>
        <link rel="stylesheet" href="stylesheets/font-awesome.min.css">
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
    </head>
    <body>
      <div class="wrapper" data-updated="<?php echo $profileInfo[15]; ?>">
      <? if ($status != "error") {
          if($size = @getimagesize("images/banner/".$profileInfo[1].".png")) {
            $ratio = $size[1] / $size[0];
            $real_size = 225 / $ratio;
          }
      ?>
      <div class="banner" style="background: url('images/banner/<?php echo $profileInfo[1]; ?>.png?<? echo date('l jS \of F Y h:i:s A'); ?>') no-repeat; background-position: 50% 50%" data-img-width="<?php echo $real_size; ?>" data-img-height="225">
      <? if(getLoggedIn() == $profileInfo[1]){?>
      <div class="overlay"></div><? } ?>
      <?php if(!file_exists("images/banner/".$profileInfo[1].".png")) { ?><img data-src="holder.js/100%x225/text: click on the edit icon to update the banner" class="banner-placeholder"><? } ?>
      <div class="cover"></div>
      <div class="active-tab-timeline"></div><div class="active-tab-about"></div><div class="active-tab-new-submission"></div>
      <ul class="banner-command">
        <li><a href="#" class="tab timeline-tab" data-tab="timeline"><i class="fa fa-clock-o fa-fw"></i> Timeline</a></li>
        <li><a href="#" class="tab about-tab" data-tab="about"><i class="fa fa-newspaper-o fa-fw"></i> About</a></li>
        <li><a href="#" class="tab submission-tab" data-tab="submission"><i class="fa fa-plus-square fa-fw"></i> New Submission</a></li>
      </ul>
      </div>
      <? } ?>
      <div class="new-submission" data-tab="submission" data-id="<?php echo $profileInfo[0]; ?>">
        <h1 class="about-content">New Submission</h1>
        <p>Under Construction right now will be completely workable tonight. This will replace the old add new submission page.</p>
        <div class="about-content">
                <input type="text" name="title" class="submission-input" 
                    placeholder="Title" autocomplete="off">
                <div class="input-box the-stuff center">
                    <p style="text-align center;">Please choose an image:<br> <input type="file" name="uploadFile" id="uploader"></p>
                </div>
                <input type="text" name="tags" class="submission-input" 
                    placeholder="tags (seperate them by commas (,)" autocomplete="off">
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
        </div>
      </div>
      <div class="about" data-tab="about" data-id="<?php echo $profileInfo[0]; ?>">
      <h1 class="about-content">About</h1>
      <div class="about-content">
      <p class="about-title" style="margin-bottom: 8px; border-bottom: 1px solid #dedede; padding: 10px">Short Bio</p>
      <p><span class="bio-edit" data-bio="<? echo $profileInfo[10] ?>"><? echo $profileInfo[10] ?></span><? if(getLoggedIn() == $profileInfo[1]){?><img src="images/edit-icon.png" class="bio-edit" data-edit="bio-edit"/><? } ?></p>
      </div>
      <div class="about-content">
      <p class="about-title" style="margin-bottom: 8px; border-bottom: 1px solid #dedede; padding: 10px">Basic Info</p>
      <p>First Name: <? if(getLoggedIn() == $profileInfo[1]){?><img src="images/edit-icon.png" class="edit" data-edit="fname-edit"/><? } ?><span style="position: absolute; right: 18px;" class="tagger fname-edit" data-fname="<? echo $profileInfo[4]; ?>"><?php echo $profileInfo[4]; ?></span></p>
      <p>Last Name: <? if(getLoggedIn() == $profileInfo[1]){?><img src="images/edit-icon.png" class="edit" data-edit="lname-edit"/><? } ?><span style="position: absolute; right: 18px;" class="tagger lname-edit" data-lname="<? echo $profileInfo[5]; ?>"><? echo $profileInfo[5]; ?></span></p>
      <p>Location: <? if(getLoggedIn() == $profileInfo[1]){?><img src="images/edit-icon.png" class="edit" data-edit="location-edit"/><? } ?><span style="position: absolute; right: 18px;" class="tagger location-edit" data-location="<? echo $profileInfo[7] ?>"><? echo $profileInfo[7] ?></span></p>
      <p>Artistry: <? if(getLoggedIn() == $profileInfo[1]){?><img src="images/edit-icon.png" class="edit" data-edit="artist-edit"/><? } ?><span style="position: absolute; right: 18px;" class="tagger artist-edit" data-artist="<? echo $profileInfo[11]; ?>"><? echo $profileInfo[11] ?></span></p>
      </div>
      <div class="about-content">
      <p class="about-title" style="margin-bottom: 8px; border-bottom: 1px solid #dedede; padding: 10px">Links</p>
      <!-- EDIT PROFILE.JS TO UDPATE SPAN INSTEAD OF P -->
      <p><span class="twitter-edit" data-twitter="<?php echo $profileInfo[12]; ?>"><a href="https://twitter.com/<?php echo $profileInfo[12]; ?>" class="twitter-edit"><i class="fa fa-twitter fa-fw"></i> Twitter</a></span> <? if(getLoggedIn() == $profileInfo[1]){?><img src="images/edit-icon.png" class="twitter-edit" data-edit="twitter-edit"/><? } ?></p>
      <p><span class="facebook-edit" data-facebook="<?php echo $profileInfo[14]; ?>"><a href="https://www.facebook.com/<?php echo $profileInfo[14];?>" class="facebook-edit"><i class="fa fa-facebook fa-fw"></i> Facebook</a></span> <? if(getLoggedIn() == $profileInfo[1]){?><img src="images/edit-icon.png" class="facebook-edit" data-edit="facebook-edit"/><? } ?></p>
      <p><span class="website-edit" data-website="<?php echo $profileInfo[13]; ?>"><a href="http://<? echo $profileInfo[13]; ?>"><i class="fa fa-link fa-fw"></i> Website</a></span> <? if(getLoggedIn() == $profileInfo[1]){?><img src="images/edit-icon.png" class="website-edit" data-edit="website-edit"/><? } ?></p>
      </div>
      </div>
      <div class="content timeline" id="#container" data-tab="timeline"> 
      <?
       $i = 0;
       if(isset($submissions)) {
       foreach($submissions as $submission) { 
       ?>
        <div class="content-wrapper" data-id="<?php echo $submission['id']; ?>">
          <? if($submission['medium'] == 'image') {
            ?>
          <div class="image-holder">
              <img class="content-presentation picture"
                src='submissions/image/<?php echo $submission['filename'].".".$submission['extension']; ?>' />
              <div class="image-overlay" data-id="<?php echo $submission['id']; ?>"></div>
          </div>
          <? } ?>
          <? if($submission['medium'] == 'text') {
            ?>
          <h1 class="clickable"><a href="submission.php?id=<?php echo $submission['id']; ?>"><? echo $submission['title']; ?></a></h1>
          <p class="content-date">On <?php echo interpretDate($submission['date_submitted']); ?></p>
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
          <h1 class="clickable"><a href="submission.php?id=<?php echo $submission['id']; ?>"><? echo $submission['title']; ?></a></h1>
          <p class="content-date">On <?php echo interpretDate($submission['date_submitted']); ?></p>
          <div class="content-presentation video2">
           <iframe width="100%" height="345px" src="//www.youtube.com/embed/<?php echo $youtubeID; ?>" frameborder="0" allowfullscreen></iframe>
          </div>
          <? } ?>
          <? if($submission['medium'] == 'audio') {
            $track_url = $submission['filename'];
            ?>
          <h1 class="clickable"><a href="submission.php?id=<?php echo $submission['id']; ?>"><? echo $submission['title']; ?></a></h1>
          <p class="content-date">On <?php echo interpretDate($submission['date_submitted']); ?></p>
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
          <?
          } ?>
       </div>
       <?
        $i += 1;
         if($i == 3) {
            $i = 0;   
         }        
       }
       }?>
      </div>
      </div>
     <? if ($status != "error") { ?>
     <div class="right-sidebar">
        <div class="profile-picture">
          <? if(getLoggedIn() == $profileInfo[1]){?><img src="images/edit-icon.png" class="picture-edit" /><? } ?>
          <?php if(!file_exists("images/banner/".$profileInfo[1].".png")) { ?>
          <img class="profile-picture" data-src="holder.js/150x150/text: click on the edit icon to update the profile picture">
          <? } else {?>
          <img class="profile-picture" src="images/profile/<? echo $profileInfo[1]; ?>.png?<? echo date('l jS \of F Y h:i:s A'); ?>">
          <? } ?>
          <p class="profile-name"><?php echo $profileInfo[4]." ".$profileInfo[5] ?></p>
          <p class="profile-job"><? echo grammarNazi($profileInfo[11]);  ?></p>
          <? if(getLoggedIn() == $profileInfo[1]){?>
          <a href="logout.php" class="right-sidebar-button">Logout</a>
          <? } ?>
        </div>
        <div class="profile-info" data-account-id="<?php echo $profileInfo[1] ?>">
            <p class="title">Recently added</p>
            <?php
            $lasts = selectLastFiveSubmissions($profileInfo[1]);
            foreach($lasts as $last) { ?>
            <p class="profile-info submission-list"><a href="submission.php?id=<?php echo $last['id']; ?>"><?php echo $last['title']; ?></a></p>
            <? } ?>
        </div>
        <div class="profile-info" data-account-id="<?php echo $profileInfo[1] ?>"><p class="title">Links</p>
          <p class="profile-info submission-list twitter"><a href="https://twitter.com/<? echo $profileInfo[12] ?>"><i class="fa fa-twitter fa-fw"></i> Twitter</a></p>
          <p class="profile-info submission-list facebook"><a href="https://facebook.com/<? echo $profileInfo[14] ?>"><i class="fa fa-facebook fa-fw"></i> Facebook</a></p>
          <p class="profile-info submission-list website"><a href="http://<? echo $profileInfo[13] ?>"><i class="fa fa-link fa-fw"></i> Website</a></p>
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
    <input type="file" name="uploadFile" id="profile-uploader" class="hidden">
    <input type="file" name="uploadFile" id="banner-uploader" class="hidden">
    <script src="scripts/mousewheel.js"></script>
    <link rel="stylesheet" href="scripts/perfect-scrollbar-0.5.7/min/perfect-scrollbar.min.css" />
    <script src="scripts/perfect-scrollbar-0.5.7/min/perfect-scrollbar.min.js"></script>
    <script src="scripts/login-register.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    <script src="scripts/spotlightArtist.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    <script src="scripts/imagesloaded.pkgd.min.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    <script src="scripts/masonry.pkgd.min.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    <script src="scripts/holder.js"></script>
    <script src="scripts/profile.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    </body>
</html>