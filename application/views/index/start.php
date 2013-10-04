<div class="container">
    <form class="box">
        <div>
            <img class="profile_pic" src='<?php echo $profile_pic;?>' />
            <h3>Hi <?php echo $user_profile->first_name;?>! </h3>
        </div>
        <div class="clear"></div>
        <div>
           <a href="<?php echo site_url('pages/newpost');?>" class="btn"> Post to one page </a><br />
        </div>
        <div class="divLogout">
            <a href="<?php echo $logout_url;?>" class="btn btn-facebook"><i class="icon-facebook"></i> | Disconnect from Facebook</a>
        </div>
    </form>
</div> <!-- /container -->
