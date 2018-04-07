<?php 
require_once('header.php');
require_once('wp-rest-api-app.php');

$app = new WPRestAPI_App();
$app->login();
?>

<div class="row">
<form method='post' class="col-md-6">
	<div class="form-group">
		<label for="exampleInputEmail1">Username / Email address</label>
		<input type="text" name="username" class="form-control" placeholder="Enter username or email">
		<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
	</div>
	<div class="form-group">
		<label for="exampleInputPassword1">Password</label>
		<input type="password" name="password" class="form-control" placeholder="Password">
	</div>
	<button type="submit" class="btn btn-primary">Log In</button>
</form>
</div>

<?php
require_once('footer.php');