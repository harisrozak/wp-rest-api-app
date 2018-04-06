<?php 
require_once('header.php');
require_once('post.php');

$posts = new WPRestAPI_Post();
$archive = $posts->get_archive();

// echo "<pre>";
// print_r($archive);
// echo "</pre>";

if(is_array($archive)): foreach ($archive as $post):
?>

<div class="card">
	<div class="card-body">
		<h5 class="card-title"><?php echo $post->title->rendered ?></h5>
		<h6 class="card-subtitle mb-2 text-muted">
			<span class="badge badge-primary"><?php echo ucfirst($post->status) ?></span>
			<?php echo date('d F Y', strtotime($post->date)) ?>
		</h6>
		<p class="card-text"><?php echo $posts->excerpt($post->excerpt->rendered) ?></p>
		<a href="<?php echo $post->link ?>" target="blank" class="card-link">Continue Reading</a>
		<a href="edit-post.php?id=<?php echo $post->id ?>" class="card-link">Edit</a>
		<a href="delete-post.php?id=<?php echo $post->id ?>" class="card-link">Delete</a>
	</div>
</div>

<?php 
endforeach;

else: 
	echo $archive; 
endif;

require_once('footer.php');