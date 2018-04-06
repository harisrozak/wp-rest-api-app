<?php
require_once('wp-rest-api-app.php');

Class WPRestAPI_Post extends WPRestAPI_App {
	function __construct() {
		parent::__construct();
	}

	public function get_archive() {
		$posts = $this->curl_get(
			$this->site . 'wp-json/wp/v2/posts/',
			array('status' => 'any'),
			true
		);

		// notices
		if($posts === false) {
			return $this->notice_login();
		}
		else if(empty($posts)) {
			return $this->notice_empty();
		}

		// data
		return $posts;
	}
}