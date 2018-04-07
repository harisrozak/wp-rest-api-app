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
		if(empty($posts)) {
			return $this->notice_empty();
		}

		// data
		return $posts;
	}

	public function get_status_color($status) {
		switch ($status) {
			case 'publish':
				echo 'primary';
				break;

			case 'draft':
				echo 'secondary';
				break;

			case 'private':
				echo 'warning';
				break;
			
			default:
				echo 'dark';
				break;
		}
	}

	public function add_post() {
		// no post
		if(! $_POST) return false; 

		// empty content
		if($_POST['title'] == '' || $_POST['status'] == '' || $_POST['content'] == '') {
			echo '<div class="alert alert-danger" role="alert">Please fill all fields!</div>';
			return false;
		}

		// add or edit
		if(isset($_POST['post_id']) && intval($_POST['post_id']) > 0) {
			$url_entry_point = $this->site . 'wp-json/wp/v2/posts/' . intval($_POST['post_id']);
		}
		else {
			$url_entry_point = $this->site . 'wp-json/wp/v2/posts/';	
		}

		// do curl
		$response = $this->curl_post(
			$url_entry_point,
			array(
				'title' => $_POST['title'],
				'status' => $_POST['status'],
				'content' => $_POST['content'],
			)
		);

		// success or not
		if(isset($response->id)) {
			$string = '<div class="alert alert-primary" role="alert">Your post has been saved!. ';
			$string.= 'Now you can <a href="edit-post.php?id=' . $response->id . '">edit your post</a> ';
			$string.= 'or continue to <a href="archive.php">view the archive</a></div>';
			echo $string;

			require_once('footer.php');
			exit();
		}
		else {
			echo '<div class="alert alert-danger" role="alert">' . $response->message . '</div>';
		}
	}

	public function get_post($post_id = 0) {
		// invalid id notice
		if(intval($post_id) <= 0) {
			echo $this->notice_empty();
		}

		$post = $this->curl_get(
			$this->site . 'wp-json/wp/v2/posts/' . intval($post_id),
			array('status' => 'any'),
			true
		);

		// notices
		if(empty($post)) {
			echo $this->notice_empty();
		}

		// data
		return $post;
	}

	public function delete_post($post_id) {
		// invalid id notice
		if(intval($post_id) <= 0) {
			echo $this->notice_empty();
		}

		$post = $this->curl_delete(
			$this->site . 'wp-json/wp/v2/posts/' . intval($post_id),
			true
		);

		// data
		return $post;
	}

	public function trash_post($post_id) {
		// invalid id notice
		if(intval($post_id) <= 0) {
			echo $this->notice_empty();
		}

		$post = $this->curl_delete(
			$this->site . 'wp-json/wp/v2/posts/' . intval($post_id),
			false
		);

		// data
		return $post;
	}
}