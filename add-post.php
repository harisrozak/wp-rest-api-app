<?php 
require_once('header.php');
require_once('wp-rest-api-post.php');

$posts = new WPRestAPI_Post();
$posts->add_post();
?>

<div class="row">
<form method='post' class="col-md-8">
	<div class="form-group">
		<label for="exampleFormControlInput1">Title</label>
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
		<label for="exampleFormControlTextarea1">Post Content</label>
		<textarea name="content" class="form-control" rows="5" placeholder="Your Content"></textarea>
	</div>	
	<input type="submit" class="btn btn-primary" value="Submit Post">
</form>
</div>

<?php
require_once('footer.php');