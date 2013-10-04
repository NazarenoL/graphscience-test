<div class="box">
	<h2>Creating New Post</h2>
	<p style="font-weight: bold;">to the page: <?php echo $page->name;?></p>
    <form accept-charset="UTF-8" action="<?php echo site_url('pages/newPost/' . $page->id);?>" method="POST">
        <p>
        	<label class="requiredInput">Post</label>
        	<textarea style="width:100%" id="message" name="message" placeholder="Type in your post" rows="5"></textarea>
        </p>
        <p>
			<label>Link</label>
        	<input type="text" name="link" class="input-large" value="http://" />
        </p>
        <p>
			<label>
                Targeting <em>(Leave empty for No targeting)</em>
            </label>
            <select name="targeting[]" class="multiselect" multiple="multiple">
                <option value="AR">Argentina</option>
                <option value="US">United States</option>
                <option value="GB">Great Britain</option>
                <option value="MX">Mexico</option>
            </select>
			
        </p>
        <p>
            <label class="checkbox">
                <input type="checkbox" name="published" id="published" value="false" />
                Post as Non-Published (Invisible)
            </label>
        </p>
        <p>
            <label>Schedule</label>
            <div class="input-append date form_datetime">
                <input size="16" type="text" name="schedule" value="" readonly>
                <span class="add-on"><i class="icon-remove"></i></span>
                <span class="add-on"><i class="icon-calendar"></i></span>
            </div>
		<p>
			<span class="requiredInput">* Required</span>
		</p>
		<p>
	        <button class="btn btn-info" type="submit">Post New Message</button>
    	</p>
    </form>
	<div class="clear"></div>
</div>
