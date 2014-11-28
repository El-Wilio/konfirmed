<?php include_once('../code/backbone.php');

if(isLoggedIn()) {
  $status = "is_logged_in";
}
else {
  $status = "is_logged_in";  
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Konfirmed</title>
        <meta charset="UTF-8">
        <meta class="viewport" name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
        <meta http-equiv="pragma" content="no-cache" />
        <link rel="stylesheet" href="stylesheets/style4.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="scripts/editProfile.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
        <script src="scripts/redirect.js?<?php echo date('l jS \of F Y h:i:s A'); ?>"></script>
    </head>
    <body>
      <div class="wrapper">
        <?php include('topnav.php'); ?>
        <div class="info">
            <a href="/temp/webroot" class="logo"></a>
            <p>Thank you for logging in for the first time. Please take a few minutes of your time to fill up in the info.</p>
        </div>
        <div class="content">
            <? if($status == "error") { ?>
                <p>I'm sorry, but you have no business here</p>
            <?} else { ?>
            <div class="image">
              <img class="profile-picture" alt="<?php echo selectLoggedInAccountID().'.png'; ?>" src="<?php if (file_exists("images/profile/tmp/".selectLoggedInAccountID().".png")) { echo "images/profile/tmp/".selectLoggedInAccountID().".png";} else { echo "images/profile/tmp/empty.png"; }?>">
                <br>Please choose a profile picture:<br> <input type="file" name="uploadFile" id="uploader">
            </div>
            <!-- first name -->
            <div class="form">
            <input type="text" name="fname" class="submission-input " 
                placeholder="First Name" autocomplete="off" />
            <!-- last name --> 
            <input type="text" name="lname" class="submission-input " 
                placeholder="Last Name" autocomplete="off" />
            <!-- location here -->    
            <input type="text" name="location" class="submission-input large" 
                placeholder="Location" autocomplete="off" />
            <!-- occupation here -->
            <input type="text" name="occupation" class="submission-input large" 
                placeholder="Occupation" autocomplete="off" />
            <!--birthday here -->
            <div class="date-holder">
                <b style="margin-left: 5px; margin-bottom: 5px;">Birthday:</b><br><select name="year" class="date-input year"> 
                    <?php for($i = 1900; $i < 2015; $i++) { ?>
                    <option value="<?php echo $i ?>"><? echo $i ?></option>
                    <? } ?>
                </select><select name="month" class="date-input month"> 
                    <?php for($i = 1; $i <= 12; $i++) {
                      $monthName = date('M', mktime(0, 0, 0, $i, 10));
                    ?>
                    <option value="<?php echo $i ?>"><? echo $monthName; ?></option>
                    <? } ?>
                </select><select name="day" class="date-input day"> 
                    <?php for($i = 1; $i <= 31; $i++) { ?>
                    <option value="<?php echo $i ?>"><? echo $i ?></option>
                    <? } ?>
                </select>
            </div>
            <!-- gender here -->
            <input name="gender" type="radio" value="M"> <span class="gender">Male</span>
            <input name="gender" type="radio" value="F"> <span class="gender">Female</span>
            <p class="warning">
            By clicking Submit you agree to use the information above as your profile information.
            </p>
            <button class="send-information">Submit</button></div>
              <? } ?>
            </div>
        </div>
    </body>
</html>