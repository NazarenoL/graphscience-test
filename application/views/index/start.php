<div class="container">
    <form class="box">
        <div>
            <img class="profile_pic img-rounded" src='<?php echo $profile_pic;?>' />
            <h3>Hi <?php echo $user_profile->first_name;?>! </h3>
        </div>
        <div class="clear"></div>
        <div>
           <a href="<?php echo site_url('pages');?>" class="btn"> View and post to your pages </a><br />
           <a href="<?php echo site_url('friends');?>" class="btn"> View and post to your friends </a><br />
           <a href="<?php echo site_url('posts/newPost/person/' . $userId);?>" class="btn"> Post to your timeline </a><br />
        </div>
        <div class="divLogout">
            <a href="<?php echo $logout_url;?>" class="btn btn-facebook"><i class="icon-facebook"></i> | Disconnect from Facebook</a>
        </div>
    </form>
</div> <!-- /container -->
