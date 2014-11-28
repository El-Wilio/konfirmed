<?php 

require_once('../code/backbone.php');
require_once('../code/scripts/register.php');
require_once('../code/scripts/login.php');
require_once('../code/scripts/editProfile.php');
require_once('../code/scripts/Soundcloud.php');

if($_GET['login'] == 'true') {
  if(isProfileEmpty()) {
    header('Location: http://www.konfirmed.com/temp/webroot/editProfile.php');
    die();
  }
  else {
    header('Location: http://www.konfirmed.com/temp/webroot/profile.php');
    die();
  }
}

$client = new Services_Soundcloud('f10232de97859df1da5abb9d3965b225', '7f0106a2991fd2026161fda2ccce1ab5');


if($_POST['type'] == 'sendRegistrationInfo') {

    echo register($_POST['email'], $_POST['email_confirmation'], $_POST['password'], $_POST['password_confirmation']);

}

if($_POST['type'] == 'checkEmailAvailability') {
    
    if(!checkForAvailableUsername($_POST['email'])) { echo 'This email was already taken';}
    
}

if($_POST['type'] == 'sendEmail') {

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    
    $message = "<pre>" . $_POST['message'] . "</pre><br><br><b>sent from:</b> " . $_POST['email'];
    $result = mail ('kanmi@konfirmed.com' , $_POST['subject'], $message, $headers);
    if($result) echo "success";
    else echo "failure";
    
}

if($_POST['type'] == 'search') {
    $submissions = search($_POST['query']);
    foreach($submissions as $submission) { ?>
    
    <p class="text"><a href="submission.php?id=<?php echo $submission['id']; ?>"><?php echo $submission['title']; ?></p>
    
    <? } 
    
}

if($_POST['type'] == 'iconPopulator') {
    
   $genres = selectMediumGenres($_POST['mediumID']);
   $artists = selectPopularArtists($_POST['mediumID']);
   $submissions = selectPopularSubmissions($_POST['mediumID']);
   $spotlights = selectSpotlightSubmissionsByMedium($_POST['mediumID']);
   $submissions = selectGroupSubmissionMedium($_POST['mediumID'], 0, 10);
   
   echo '<a href="#"><img src="images/x-icon2.png" class="close-selected-category" width="20px" height="auto"></a>';
   echo '<div class="spotlights">';
   echo '<h1>Spotlights</h1>';
   foreach($spotlights as $spotlight) {
    $profile = selectProfile($spotlight['id_account']);
   ?>
    <div class="spotlight-view">
        <? if($_POST['mediumID'] == '1') { ?>
        <div class="container" style="background: url('http://www.konfirmed.com/temp/webroot/submissions/image/<?php echo $spotlight["filename"].".".$spotlight["extension"]; ?>') center center no-repeat"></div>
        <? } ?>
        <? if($_POST['mediumID'] == '4') {
            $track_url = $spotlight['filename'];
        ?>
            <div class="container content-audio-<?php echo $spotlight['id']; ?>">
                <script>
                    $(function() {
                    var track_url = '<?php echo $track_url; ?>';
                    var real_url = "test";
                    SC.initialize({
                        client_id: "f10232de97859df1da5abb9d3965b225"
                      });
                    SC.get('/resolve', { url: track_url }, function(track) {
                      SC.get('/tracks/' + track.id + '/comments', function(comments) {
                        console.log(track.id);
                        real_url = "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/"+track.id+"&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true";
                        $('.content-audio-<?php echo $spotlight['id']; ?>').html('<iframe width="100%" height="450" scrolling="no" frameborder="no" src="'+real_url+'"></iframe>');
                        for (var i = 0; i < comments.length; i++) {
                        }
                      });
                    });
                    });
                </script>
            </div>
            <? } ?>
            <? if($_POST['mediumID'] == '2') { 
            $youtubeID = end(explode("/", $spotlight['filename']));
            ?>
                <div class="container">
                    <iframe width="100%" height="100%" src="//www.youtube.com/embed/<?php echo $youtubeID; ?>" frameborder="0" allowfullscreen></iframe>
                </div>
            <? } ?>
            <? if($_POST['mediumID'] == '3') { 
            ?>
                <div class="container" style="border: 1px solid #BABABA; padding: 10px; border-radius: 5px;">
                  <?
                  $handle = fopen('submissions/'.$spotlight['medium'].'/'.$spotlight['filename'].'.txt', "r");
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
            <div class="container-info" style="color: white;">
                <a href="submission.php?id=<?php echo $spotlight['id']; ?>" style="color: white;"><b><?php echo $spotlight['title'] ?></b></a> by <a href="profile.php?id=<?php echo $profile[1]; ?>" style="color: white;"><b><?php echo $profile[4]. " ". $profile[5]; ?></b></a>
                <span style="right: 0; position: absolute; color: white;"><?php echo interpretDate($spotlight['date_submitted']); ?></span>
            </div>
    </div>
  <?
   }
   echo '</div>';
   echo '<div class="submissions">';
   echo '<h1>Submissions</h1>';
   foreach($submissions as $submission) {
    $profile = selectProfile($submission['id_account']); ?>
    <div class="submission-view">
        <? if($_POST['mediumID'] == '1') { ?>
        <div class="container" style="background: url('http://www.konfirmed.com/temp/webroot/submissions/image/<?php echo $submission["filename"].".".$submission["extension"]; ?>') top center no-repeat"></div>
        <? } ?>
        <? if($_POST['mediumID'] == '4') {
            $track_url = $submission['filename'];
        ?>
            <div class="container content-audio-<?php echo $submission['id']; ?>">
                <script>
                    $(function() {
                    var track_url = '<?php echo $track_url; ?>';
                    var real_url = "test";
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
            <? if($_POST['mediumID'] == '2') { 
            $youtubeID = end(explode("/", $submission['filename']));
            ?>
                <div class="container">
                    <iframe width="100%" height="100%" src="//www.youtube.com/embed/<?php echo $youtubeID; ?>" frameborder="0" allowfullscreen></iframe>
                </div>
            <? } ?>
            <? if($_POST['mediumID'] == '3') { 
            ?>
                <div class="container" style="border: 1px solid #BABABA; padding: 10px; border-radius: 5px;">
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
            <div class="container-info">
                <a href="submission.php?id=<?php echo $submission['id'] ?>"><b><?php echo $submission['title'] ?></b></a> by <a href="profile.php?id=<?php echo $profile[1]; ?>"><b><?php echo $profile[4]. " ". $profile[5]; ?></b></a>
                <span><?php echo interpretDate($submission['date_submitted']); ?></span>
            </div>
    </div> <?
    
    }
    
    echo '</div>';
}


if($_POST['type'] == 'login') {
   if(login($_POST['email'], $_POST['password'])) {
        echo "success";
    }
    else {
        echo "error";
    }
}

if($_POST['type'] == 'setProfile') {

  /** complete validation process **/
  
  //validate first name
    if(preg_match("/^[A-Z]'?[-a-zA-Z]([a-zA-Z])*$/", $_POST['fname'] != 1)) {
      die('error');
    }
  //validate last name
    if(preg_match("/^[A-Z]'?[-a-zA-Z]([a-zA-Z])*$/", $_POST['lname'] != 1)) {
      die('error');
    }
    
  //valide gender
  if($_POST['gender'] != 'M' && $_POST['gender'] != 'F') {
    die('gender_error');
  }
  
  //validate picture
  if(!file_exists('images/profile/tmp/'.$_POST['displayPic'])) {
    die('picture_error');
  }
  //validate location
  
  //validate birthdate
  
  
  $year = $_POST['year'];
  if(intval($_POST['day']) < 10) {
    $day= '0'.$_POST['day'];  
  }
  else {
    $day = $_POST['day'];  
  }
  if(intval($_POST['month']) < 10) {
    $month = '0'.$_POST['month'];  
  }
  else {
    $month = $_POST['month'];  
  }

  $birthday = $year.'-'.$month.'-'.$day;
  
  rename('images/profile/tmp/'.$_POST['displayPic'], 'images/profile/'.$_POST['displayPic']);
  
  echo updateProfile(1,$_POST['fname'],$_POST['lname'],$_POST['displayPic'],$_POST['loc'],$_POST['gender'],$birthday,$_POST['bio'],$_POST['occupation']);  
}

if($_POST['type'] == 'temp_profile') {
  
  $new_filename = selectLoggedInAccountID().'.png';
  
  $target_dir = "images/profile/tmp/";
  $target_dir = $target_dir . $new_filename;
  
  if(file_exists($target_dir)) {
    unlink($target_dir);
  }
  
  foreach($_FILES as $file) {
    if (move_uploaded_file($file['tmp_name'], $target_dir)) {
      echo $new_filename;
    } else {

    }
  }
}

if($_POST['type'] == 'temp_submission') {
  
  $new_filename = selectLoggedInAccountID().'.'.$_POST['extension'];
  
  $target_dir = "submissions/image/tmp/";
  $target_dir = $target_dir . $new_filename;
  
  if(file_exists($target_dir)) {
    unlink($target_dir);
  }
  
  foreach($_FILES as $file) {
    if (move_uploaded_file($file['tmp_name'], $target_dir)) {
      echo $new_filename;
    } else {

    }
  }
}

if($_POST['type'] == 'rate-it') {
  
  $profile = selectCurrentProfile();
  $id = $profile[1];
  insertRating($_POST['id'], $id, $_POST['rating']);
 
}

if($_POST['type'] == 'newImageSubmission') {
  
  $title = $_POST['title'];
  $genre = $_POST['genre'];
  $genres = explode(',', $genre);
  $tag = $_POST['tag'];
  $tags = explode(',', $tag);
  foreach($tags as &$tag) {
    $tag = trim($tag);
  }
  $mediumID = $_POST['mediumID'];
  $file_path = $_POST['file'];
  $extension = end(explode('.', $file_path));  
  rename($file_path, 'submissions/image/'.fixFilename($title, $extension, $mediumID).'.'.$extension);

  $submission = array(
    'id_account' => selectLoggedInAccountID(),
    'id_medium' => $mediumID,
    'title' => $title,
    'filename' => fixFilename($title, $extension, $mediumID),
    'extension' => $extension,
    'genres' => $genres,
    'tags' => $tags
    );
    
    insertSubmission($submission);

}

if($_POST['type'] == 'newVideoSubmission') {
  
  //test if it's youtube url here
  
  $title = $_POST['title'];
  $genre = $_POST['genre'];
  $genres = explode(',', $genre);
  $tag = $_POST['tag'];
  $tags = explode(',', $tag);
  foreach($tags as &$tag) {
    $tag = trim($tag);
  }
  
  $mediumID = $_POST['mediumID'];
  $videoUrl = $_POST['url'];
  
  if(preg_match('/^(http\:\/\/)?(www\.youtube\.com|youtu\.?be)\/.+$/', $videoUrl) == 0) {
        
    die('wrong url');
    
  }

  $submission = array(
    'id_account' => selectLoggedInAccountID(),
    'id_medium' => $mediumID,
    'title' => $title,
    'filename' => $videoUrl,
    'extension' => $extension,
    'genres' => $genres,
    'tags' => $tags
    );
    
    insertSubmission($submission);

}

if($_POST['type'] == 'newAudioSubmission') {
  
  //test if it's youtube url here
  
  $title = $_POST['title'];
  $genre = $_POST['genre'];
  $genres = explode(',', $genre);
  $tag = $_POST['tag'];
  $tags = explode(',', $tag);
  foreach($tags as &$tag) {
    $tag = trim($tag);
  }
  
  $mediumID = $_POST['mediumID'];
  $videoUrl = $_POST['url'];
  
  if(preg_match('/^https?:\/\/(soundcloud.com|snd.sc)\/(.*)$/', $videoUrl) == 0) {
        
    die('wrong url');
    
  }

  $submission = array(
    'id_account' => selectLoggedInAccountID(),
    'id_medium' => $mediumID,
    'title' => $title,
    'filename' => $videoUrl,
    'extension' => $extension,
    'genres' => $genres,
    'tags' => $tags
    );
    
    insertSubmission($submission);

}

if($_POST['type'] == 'newTextSubmission') {
  
  //test if it's youtube url here
  
  $title = $_POST['title'];
  $genre = $_POST['genre'];
  $genres = explode(',', $genre);
  $tag = $_POST['tag'];
  $tags = explode(',', $tag);
  foreach($tags as &$tag) {
    $tag = trim($tag);
  }
  
  $mediumID = $_POST['mediumID'];
  $text = $_POST['text'];
  
  $submission = array(
    'id_account' => selectLoggedInAccountID(),
    'id_medium' => $mediumID,
    'title' => $title,
    'filename' => fixFilename($title, 'txt', $mediumID),
    'extension' => 'txt',
    'genres' => $genres,
    'tags' => $tags,
    'text' => $text
    );
    
    insertSubmission($submission);

}

if($_POST['type'] == 'getSubmissions') {
   $submissions = selectGroupSubmission($_POST['id'], $_POST['start'], 3);
   foreach($submissions as $submission) { ?>
        <div class="content-wrapper" data-id="<?php echo $submission['id']; ?>">
          <? if($submission['medium'] == 'image') {
            ?>
          <div class="content-presentation picture" 
            style="background: url('submissions/image/<?php echo $submission['filename'].".".$submission['extension']; ?>') center center no-repeat;">
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
          <? if($submission['medium'] == 'text') { ?>
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
          <h1 class="clickable"><a href="submission.php?id=<?php echo $submission['id']; ?>"><? echo $submission['title']; ?></a></h1>
          <p class="content-date">On <?php echo interpretDate($submission['date_submitted']); ?></p>  
       </div>
       <? }
}

if($_POST['type'] == 'deleteSubmission') {
  
  echo deleteSubmission($_POST['id']);

}

if($_POST['type'] == 'getMediumGenres') {
  $genres = selectMediumGenres($_POST['mediumID']);
  foreach($genres as $genre) { ?>
  <p class="submission-checkbox"><input name="genre" type="checkbox" value="<?php echo $genre['id'] ?>"><?php echo $genre['genre']; } ?></p>
<?

}

if($_POST['type'] == 'remove-artist') {
    echo $_POST['id'];
    deleteSpotlightArtist($_POST['id']);
 
}

if($_POST['type'] == 'insert-artist') {
    echo $_POST['id'];
    insertSpotlightArtist($_POST['id']);
 
}

if($_POST['type'] == 'remove-submission') {
    echo $_POST['id'];
    deleteSpotlightSubmission($_POST['id']);
 
}

if($_POST['type'] == 'insert-submission') {
    echo $_POST['id'];
    insertSpotlightSubmission($_POST['id']);
 
}

if($_POST['type'] == 'requestBrowse') {

    $submissions = searchByGenre($_POST['genre']);
    
    foreach($submissions as $submission) {
        $profile = selectProfile($submission['id_account']);
    ?>
    
    <p class="text"><b><a href="submission.php?id=<?php echo $submission['id']; ?>"><?php echo $submission['title']; ?></b> by <b><a href="profile.php?id=<?php echo $profile[1]; ?>"><?php echo $profile[4]. " ". $profile[5]; ?></b></p>
    
    <? } ?>
    
    <a href="#"><img src="images/x-icon2.png" class="close-browse" width="20px" height="auto"></a>
    
    <? 
 
}

if($_POST['type'] == 'goBack') { ?>
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
<?
}

?>