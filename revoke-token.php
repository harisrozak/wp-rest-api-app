<?php 
require_once('header.php');
require_once('wp-rest-api-app.php');

$app = new WPRestAPI_App();
$app->revoke_token();
?>

<div class="alert alert-primary" role="alert">
 	Your token has been revoked, please <a href="login.php">login again</a> if you missing something
</div>

<?php
require_once('footer.php');