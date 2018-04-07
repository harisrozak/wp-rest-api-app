<?php 
require_once('header.php');
require_once('wp-rest-api-post.php');

$posts = new WPRestAPI_Post();
$archive = $posts->get_archive();

if(is_array($archive)): foreach ($archive as $post):
?>

<div class="card">
	<div class="card-body">
		<h5 class="card-title"><?php echo $post->title->rendered ?></h5>
		<h6 class="card-subtitle mb-2 text-muted">
			<span class="badge badge-<?php $posts->get_status_color($post->status) ?>">
				<?php echo ucfirst($post->status) ?>
			</span>
			<?php echo date('d F Y', strtotime($post->date)) ?>
		</h6>
		<p class="card-text"><?php echo $posts->excerpt($post->excerpt->rendered) ?></p>
		<a href="<?php echo $post->link ?>" target="blank" class="btn btn-sm btn-outline-dark">Continue Reading</a>
		<a href="edit-post.php?id=<?php echo $post->id ?>" class="btn btn-sm btn-outline-primary">Edit</a>
		<a href="trash-post.php?id=<?php echo $post->id ?>" class="btn btn-sm btn-outline-warning">Trash</a>
		<a href="delete-post.php?id=<?php echo $post->id ?>" class="btn btn-sm btn-outline-danger">Permanently Delete</a>
	</div>
</div>

<?php 
endforeach;

else: 
	echo $archive; 
endif;

require_once('footer.php');