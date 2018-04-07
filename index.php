<?php 
require_once('header.php');
require_once('wp-rest-api-app.php');

$app = new WPRestAPI_App();
$app->validate_token();

require_once('footer.php');