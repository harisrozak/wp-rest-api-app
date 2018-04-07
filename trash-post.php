<?php 
require_once('header.php');
require_once('wp-rest-api-post.php');

if(isset($_GET['id']) && intval($_GET['id']) > 0):

$post_id = intval($_GET['id']);
$posts = new WPRestAPI_Post();
$posts->trash_post($post_id);
?>

<div class="alert alert-primary" role="alert">
 	Post with ID <?php echo $post_id ?> has successfully trashed. <a href="archive.php">Back to archive</a>
</div>

<?php else: ?>

<div class="alert alert-danger" role="alert">
	Invalid post ID
</div>

<?php
endif;
require_once('footer.php');