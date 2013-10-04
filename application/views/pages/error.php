<div class="box">
    <h2>Error!</h2>

    <div class="alert alert-error fade in">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4>An error has ocurred while retrieving your pages (maybe you don't have any?). </h4>
        
        <php if($error != ""){
            echo 'That was bad; My friend facebook said that it was because:<br />' . $error . '<br />';
        }
        ?>
        <a href="<?php echo site_url();?>" class="btn btn-primary"> Go back to the home</a>
    </div>
    <div class="clear"></div>
</div>
