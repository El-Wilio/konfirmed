<?php 

require_once('../code/backbone.php');
require_once('../code/scripts/register.php');
require_once('../code/scripts/login.php');
require_once('../code/scripts/editProfile.php');
require_once('../code/scripts/Soundcloud.php');

$client = new Services_Soundcloud('f10232de97859df1da5abb9d3965b225', '7f0106a2991fd2026161fda2ccce1ab5');


if($_POST['type'] == 'sendRegistrationInfo') {

    echo register($_POST['email'], $_POST['email_confirmation'], $_POST['password'], $_POST['password_confirmation']);

}

if($_POST['type'] == 'checkEmailAvailability') {
    
    if(!checkForAvailableUsername($_POST['email'])) { echo 'This email was already taken';}
    
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
    if(preg_match("/^[A-Z]'?[- a-zA-Z]( [a-zA-Z])*$/", $_POST['name'] != 1)) {
      die('error');
    }
  //validate last name
  
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
  
  echo updateProfile(1,$_POST['fName'],$_POST['lName'],$_POST['displayPic'],$_POST['loc'],$_POST['gender'],$birthday,$_POST['bio'],$_POST['occupation']);  
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

if($_POST['type'] == 'getSubmissions') {
   $submissions = selectGroupSubmission($_POST['id'], $_POST['start'], 3);
   foreach($submissions as $submission) { ?>
        <div class="content-wrapper" data-id="<?php echo $submission['id']; ?>">
          <h1><?php echo $submission['title']; ?></h1>
          <? if($submission['medium'] == 'image') {
            ?>
          <div class="content-presentation <?php echo $submission['medium']; ?>" 
            style="background: url('submissions/image/<?php echo $submission['filename'].".".$submission['extension']; ?>') center center no-repeat;">
          </div>
          <? } ?>
          <? if($submission['medium'] == 'video') {
            $youtubeID = end(explode("/", $submission['filename']));
          ?>
          <div class="content-video <?php echo $submission['medium']; ?>">
           <iframe width="500px" height="315px" src="//www.youtube.com/embed/<?php echo $youtubeID; ?>" frameborder="0" allowfullscreen></iframe>
          </div>
          <? } ?>
          <? if($submission['medium'] == 'audio') {
            $track_url = $submission['filename'];
            $track = json_decode($client->get('resolve', array('url' => $track_url)));
            ?>
          <div class="content-audio <?php echo $submission['medium']; ?>">
          <iframe width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/<?php echo $track->id; ?>&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>
          </div>
          <? } ?>
          <div class="content-info">
            <div class="content-date"><?php echo interpretDate($submission['date_submitted']); ?></div>
            <div class="content-rating">5 STARS</div>
          </div>
       </div>
       <? }
}

if($_POST['type'] == 'deleteSubmission') {
  
  echo deleteSubmission($_POST['id']);

}

if($_POST['type'] == 'getMediumGenres') {
  $genres = selectMediumGenres($_POST['mediumID']);
  foreach($genres as $genre) { ?>
  <p><input name="genre" type="checkbox" value="<?php echo $genre['id'] ?>"><?php echo $genre['genre']; } ?></p>

<?

}

?>