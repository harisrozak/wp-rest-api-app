<?php 
require_once('header.php');
require_once('wp-rest-api-post.php');

$posts = new WPRestAPI_Post();
$posts->add_post();
?>

<h2>Add Post</h2><hr>

<form method='post'>
	<div class="row">
		<div class="col-md-7">
			<div class="form-group">
				<label>Title</label>
				<input type="text" name="title" class="form-control" placeholder="Your Title">
			</div>
			<div class="form-group">
				<label for="exampleFormControlTextarea1">Status</label>
				<select name="status" class="form-control">
					<option value="publish">Publish</option>
					<option value="draft">Draft</option>
					<option value="private">Private</option>
				</select>
			</div>
			<div class="form-group">
				<label>Post Content</label>
				<textarea name="content" class="form-control" rows="5" placeholder="Your Content"></textarea>
			</div>	
		</div>

		<!-- custom fields -->
		<div class="col-md-5">
			<div class="form-group">
				<label>Name</label>
				<input type="text" class="form-control" name="name" placeholder="Your Name">
			</div>
			<div class="form-group">
				<label>Address</label>
				<input type="text" class="form-control" name="address" placeholder="Your Address">
			</div>
			<div class="form-group">
				<label>Gender</label>
				<select class="form-control" name="gender">
					<option value="Man">Man</option>
					<option value="Woman">Woman</option>
				</select>
			</div>
		</div>
	</div>

	<input type="submit" class="btn btn-primary" value="Submit Post">
</form>
</div>

<?php
require_once('footer.php');