<?php 
require_once('header.php');
require_once('wp-rest-api-post.php');

$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$posts = new WPRestAPI_Post();
$posts->add_post();
$post = $posts->get_post($post_id);

?>

<div class="row">
<form method='post' class="col-md-8">
	<input type="hidden" name="post_id" value="<?php echo $post->id ?>">
	<div class="form-group">
		<label for="exampleFormControlInput1">Title</label>
		<input type="text" name="title" class="form-control" placeholder="Your Title" value="<?php echo $post->title->rendered ?>">
	</div>
	<div class="form-group">
		<label for="exampleFormControlTextarea1">Status</label>
		<select name="status" class="form-control">
			<option value="publish" <?php if($post->status == 'publish') echo "selected" ?>>Publish</option>
			<option value="draft" <?php if($post->status == 'draft') echo "selected" ?>>Draft</option>
			<option value="private" <?php if($post->status == 'private') echo "selected" ?>>Private</option>
		</select>
	</div>
	<div class="form-group">
		<label for="exampleFormControlTextarea1">Post Content</label>
		<textarea name="content" class="form-control" rows="5" placeholder="Your Content"><?php echo $post->content->rendered ?></textarea>
	</div>	
	<input type="submit" class="btn btn-primary" value="Submit Post">
</form>
</div>

<?php
require_once('footer.php');