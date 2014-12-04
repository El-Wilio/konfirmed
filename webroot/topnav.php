<div class="top-navbar-background"></div>
    <div class="top-navbar">
    <a class="menu-icon"></a>
      <ul class="navigation">
        <li><a href="/temp/webroot">Home</a></li>
        <li class="account"><a href="#">Account</a>
          <ul class="sub-navigation child-of-account">
            <?php if(isLoggedIn()) { ?>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
            <? } else {?>
            <li><a href="#" class="login-click">Login</a></li>
            <li><a href="#" class="register-click">Register</a></li>
            <? } ?>
          </ul>
        </li>
        <li class="submission"><a href="#">Submissions</a>
          <ul class="sub-navigation child-of-submission">
            <?php if(isLoggedIn()) { ?>
            <li><a href="submission.php?new">Add new submission</a></li>
            <li><a href="submission.php?edit">Manage submissions</a></li>
            <? } ?>
            <li><a href="search.php">Search</a></li>
            <li><a href="browse.php">Browse</a></li>
          </ul>
        </li>
        <li class="other"><a href="#">Other</a>
          <ul class="sub-navigation child-of-other">
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact Us</a></li>
          </ul>
        </li>
        <li class="spotlight"><a href="#">Spotlight</a>
          <ul class="sub-navigation child-of-spotlight">
            <li><a href="spotlight.php">View Spotlights</a></li>
            <?php if($page == 'profile') { ?>
            <?php if(isSpotlightedArtist($profileInfo[1]) == 'no') { ?>
            <li><a href="#" class="spotlight-it" data-id="<?php echo $profileInfo[1]; ?>">Spotlight this Artist</a></li>
            <? } else { ?>
            <li><a href="#" class="remove-it" data-id="<?php echo $profileInfo[1]; ?>">Remove this Spotlight</a></li>
            <? } } ?>
            <?php if($page == 'submission') { ?>
            <?php if(isSpotlightedSubmission($_GET['id']) == 'no') { ?>
            <li><a href="#" class="spotlight-it" data-id="<?php echo $_GET['id']; ?>">Spotlight this Submission</a></li>
            <? } else { ?>
            <li><a href="#" class="remove-it" data-id="<?php echo $_GET['id']; ?>">Remove this Spotlight</a></li>
            <? } } ?>
          </ul>
        </li>
    </ul>
</div>