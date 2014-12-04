<a href="/temp/webroot" class="home-link">K O N F I R M E D</a>
    <ul class="menu-items-2 inline center">
                <li class="second-row"><div class="image art-2 box-2"></div></li><!--
                --><li class="second-row"><div class="video photography-2 box-2"></div></li><!--
                --><li><div class="text writer-2 box-2"></div></li><!--
                --><li><div class="audio music-2 box-2"></div></li>
            </ul>
<ul class="left-sidebar-content">
    <ul class="left-sidebar-subcontent">
        <li><a href="#" class="categories-click"><i class="fa fa-user fa-lg"></i><span class="spacing">Account</span></a></li>
        <div class="stuff-block">
            <?php if(isLoggedIn()) { ?>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
            <? } else {?>
            <li><a href="#" class="login-click">Login</a></li>
            <li><a href="#" class="register-click">Register</a></li>
            <? } ?>
        </div>
    </ul>
    <ul class="left-sidebar-subcontent">
        <li><a href="#" class="categories-click"><i class="fa fa-exchange fa-lg"></i><span class="spacing">Submissions</span></a></li>
        <div class="stuff-block">
            <?php if(isLoggedIn()) { ?>
            <li><a href="submission.php?new">Add new submission</a></li>
            <li><a href="submission.php?edit">Manage submissions</a></li>
            <? } ?>
            <li><a href="search.php">Search</a></li>
            <li><a href="browse.php">Browse</a></li>
        </div>
    </ul>
    <ul class="left-sidebar-subcontent">
        <li><a href="#" class="categories-click"><i class="fa fa-star fa-lg"></i><span class="spacing">Spotlight Artists</span></a></li>
        <div class="stuff-block">
            <?php $artists = selectSpotlightArtists(); 
                foreach($artists as $artist) { ?>
                    <li><a href="profile.php?id=<?php echo $artist[1]; ?>"><?php echo $artist[4]." ".$artist[5]; ?></a><li>
            <? } ?>
        </div>
    </ul>
    <ul class="left-sidebar-subcontent">
        <li><a href="#" class="categories-click"><i class="fa fa-star fa-lg"></i><span class="spacing">Spotlight Submissions</span></a></li>
        <div class="stuff-block">
        <?php $submissions = selectSpotlightSubmissions();
            foreach($submissions as $submission) { ?>
            <li><a href="submission.php?id=<?php echo $submission['id']; ?>"><? echo $submission['title']; ?></a><li>
        <? } ?>
        </div>
    </ul>
</ul>
    