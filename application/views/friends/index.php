<div class="well box">
    <table class="table datatable">
      <thead>
        <tr>
          <th>Pic</th>
          <th>#</th>
          <th>Name</th>
          <th style="width: 150px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        
		<?php
		//Print a list of all the friends
		foreach($friends->data as $friend){
			//cast as an object
			$friend = (object) $friend;
			//Print the row
			printf('
			<tr>
	          <td><img class="img-circle" src="https://graph.facebook.com/%s/picture" /></td>
	          <td>%s</td>
	          <td><strong>%s</strong></td>
	          <td>
	              <a href="%s" class="btn btn-success">Post on timeline</a>
	              <a href="%s" class="btn">View profile</a>
	          </td>
	        </tr>',
	        $friend->id,
	        $friend->id, 
	        $friend->name,
	        site_url('posts/newPost/person/' . $friend->id),
	        'http://www.facebook.com/' . $friend->id
	        );
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
