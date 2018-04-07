<?php

Class WPRestAPI_App {
	function __construct() {
		$this->site = 'http://localhost/kajianmu/';
		$this->timeout_time = time() + 60 * 60 * 24 * 30; // 30 days
	}

	// Get cut position based on fixed position without break last word
	public function excerpt($text, $fixed = 300) {
		$excerpt = '';

		if(strlen($text) >= $fixed) {
			$pos = strpos($text, ' ', $fixed);
			$excerpt = substr($text,0,$pos);
			// delete last non alphanumeric character (save ">" if you want to save html tag)
			$excerpt = preg_replace('/[`!@#$%^&*()_+=\-\[\]\';,.\/{}|":<>?~\\\\]$/', '', $excerpt);
		}

		$excerpt = $excerpt == '' ? $text : $excerpt;
		return $this->fix_unclosed_html_tag($excerpt);
	}

	// fix_unclosed_html_tag
	protected function fix_unclosed_html_tag($string) {
		if(trim($string) == '') return '';

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML(mb_convert_encoding($string, 'HTML-ENTITIES', 'UTF-8'));
        
        return $dom->saveHTML($dom->getElementsByTagName('div')->item(0));
	}

	// check the string value, is json or not
	protected function is_json($string) {
		json_decode($string);
 		return (json_last_error() == JSON_ERROR_NONE);
	}

	// curl get json
	protected function curl_get($url, $get_args = array(), $need_auth = false) {
		$response = false;

		// 1. initialize
		$ch = curl_init();		 
		
		// 2. with get argument or not
		if(count($get_args) > 0) {
			$args = http_build_query($get_args);
			$url.= '?' . $args;
		}

		// 3. set the options, including the url
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);

		// 4. need auth or not
		if($need_auth) {
			$this->curl_header_auth($ch);
		}
		 
		// 5. execute and fetch the resulting HTML output
		$response = curl_exec($ch);
		$info = curl_getinfo($ch);

		// 6. free up the curl handle
		curl_close($ch);

		// 7. check file json
		if(! $this->is_json( $response ) ) {
			$this->notice_invalid_json_then_exit();
		}

		// 8. check curl code
		if($info['http_code'] >= 400) {
			$this->notice_400_then_exit($response);
		}

		return json_decode( $response );
	}

	// curl get json
	protected function curl_post($url, $data = array(), $check_http_code = true) {
		$response = false;

		// 1. initialize
		$ch = curl_init();	

		// 2. set post data
		$post_data = http_build_query($data);

		// 3. set the options, including the url
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

		// 4. posting data require an auth
		$this->curl_header_auth($ch);
		 
		// 5. execute and fetch the resulting HTML output
		$response = curl_exec($ch);
		$info = curl_getinfo($ch);
		 
		// 6. free up the curl handle
		curl_close($ch);

		// 7. check file json
		if(! $this->is_json( $response ) ) {
			$this->notice_invalid_json_then_exit();
		}

		// 8. check curl code
		if($info['http_code'] >= 400 && $check_http_code) {
			$this->notice_400_then_exit($response);
		}

		return json_decode( $response );
	}

	// curl delete
	protected function curl_delete($url, $permanent = false) {
		$response = false;

		// 1. initialize
		$ch = curl_init();	

		// 2. set post data
		$post_data = http_build_query(array(
			'force' => $permanent,
		));

		// 3. set the options, including the url
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); 

		// 4. posting data require an auth
		$this->curl_header_auth($ch);
		 
		// 5. execute and fetch the resulting HTML output
		$response = curl_exec($ch);
		$info = curl_getinfo($ch);
		 
		// 6. free up the curl handle
		curl_close($ch);

		// 7. check file json
		if(! $this->is_json( $response ) ) {
			$this->notice_invalid_json_then_exit();
		}

		// 8. check curl code
		if($info['http_code'] >= 400) {
			$this->notice_400_then_exit($response);
		}

		return json_decode( $response );
	}

	// curl_header_auth
	protected function curl_header_auth($ch) {
		$token = $this->get_token();

		if($token) {
			$header = array(
			    'Accept: application/json',
			    'Content-Type: application/x-www-form-urlencoded',
			    'Authorization: Bearer ' . $token
			);
			
			// pass header variable in curl method
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
	}

	// get_token
	protected function get_token() {
		if(isset($_COOKIE['wp_rest_api_app_token'])) {
			return $_COOKIE['wp_rest_api_app_token'];
		}
		
		return false;
	}

	// set_token
	protected function set_token($token) {
		setcookie('wp_rest_api_app_token', $token, $this->timeout_time, "/");
	}

	// remove_token
	public function revoke_token() {
		if(! isset($_COOKIE['wp_rest_api_app_token'])) return;
		
		unset($_COOKIE['wp_rest_api_app_token']);
		setcookie('wp_rest_api_app_token', null, -1, '/');	
	}

	// validate token
	public function validate_token() {
		$auth = $this->curl_post($this->site . 'wp-json/jwt-auth/v1/token/validate', array(), false);

		if(isset($auth->code) && $auth->code == 'jwt_auth_valid_token') {
			echo '<div class="alert alert-primary" role="alert">Your token is valid, you are good to go!</div>';
		}
		else {
			echo $this->notice_login();
		}
	}

	// empty data notice
	public function notice_empty() {
		return '<div class="alert alert-info" role="alert">No data found!</div>';
	}

	// login notice
	public function notice_login() {
		$string = '<div class="alert alert-danger" role="alert">Your token has expired, please ';
		$string.= '<a href="login.php">login with your account</a> to get your access</div>';
		return $string;
	}

	// http 400 notice
	public function notice_400_then_exit($response) {
		if(is_string($response)) {
			$response = json_decode($response);
		}

		echo '<div class="alert alert-danger" role="alert">' . $response->message . '</div>';
		require_once('footer.php');
		exit();
	}

	// http invalid json notice
	public function notice_invalid_json_then_exit() {
		echo '<div class="alert alert-danger" role="alert">Invalid json response!</div>';
		require_once('footer.php');
		exit();
	}

	// login page action
	public function login() {
		if($_POST && isset($_POST['username']) && isset($_POST['password'])) {
			$auth = $this->curl_post(
				$this->site . 'wp-json/jwt-auth/v1/token/',
				array (
					'username' => $_POST['username'],
					'password' => $_POST['password'],
				)
			);

			// success or not
			if(isset($auth->token)) {
				$this->set_token($auth->token);
				$string = '<div class="alert alert-primary" role="alert">You have successfully logged in!. ';
				$string.= '<a href="archive.php">Click here</a> to see your posts.</div>';
				echo $string;

				require_once('footer.php');
				exit();
			}
			else {
				echo '<div class="alert alert-danger" role="alert">' . $auth->message . '</div>';
			}
		}
		else {
			echo '<div class="alert alert-info" role="alert">Enter your valid username and password</div>';
		}
	}
}