<div class="well box">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Page</th>
          <th>Category</th>
          <th>New Likes / Talking About</th>
          <th style="width: 150px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        
		<?php
		//Print a list of all the pages that the user have connection
		foreach($pages as $page){
			//cast as an object
			$page = (object) $page;

			//Define the buttons to show if them depends on a PERM
			$page->buttons = "";

			//Can user Create Content?
			if(in_array("CREATE_CONTENT", $page->perms)){
				$page->buttons .= sprintf('<a href="%s" class="btn btn-success">Create Post</a>',site_url('posts/newPost/page/' . $page->id));
			}

			//View this page on a new tab
			$page->buttons .= sprintf('<a href="%s" target="_blank" class="btn">View Page</a>',$page->link);

			//Can user Create Ads?
			if(in_array("CREATE_ADS", $page->perms)){
				$page->buttons .= '<a class="btn notAvailable">Create Ad</a>';
			}

			//Print the row
			printf('
			<tr>
	          <td>%s</td>
	          <td><strong>%s</strong><br />%s</td>
	          <td>%s</td>
	          <td>%s / %s</td>
	          <td>
	              %s
	          </td>
	        </tr>',
	        $page->id,
	        $page->name, $page->about, 
	        $page->category,
	        $page->new_like_count, $page->talking_about_count,
	        $page->buttons);
		}
		?>

        
      </tbody>
    </table>
</div>

 <!-- Function not available in this demo-->
 <div class="modal hide fade" id="notavailable-dialog">
   <div class="modal-header">
        <a class="close" data-dismiss="modal">x</a>
        <h3>Function not available in this demo.</h3>
   </div>
   <div class="modal-body">
   	:( Sorry, it was developed in a few hours, without access to the Ads API.
   </div>
   <div class="modal-footer">
       <a href="#" class="btn btn-danger btn-modal btn-cancel"  data-dismiss="modal">Oh :(</a>
   </div>
 </div>
 <script>
	$(document).ready(function(){
		$(".notAvailable").on("click", function(){ $("#notavailable-dialog").modal();});
    });
</script>
