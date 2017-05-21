<?php 

namespace Core;
// store just user_id in session

class Session {

	private static $logged_id = false;
	public static $user_id; // if it's logged in
	public static $message;
	public static $vars = [];
	public static $url = [];

	// private $logged_id = false;
	// public $user_id; // if it's logged in
	// public $message;
	// public $errors = [];
	// public $vars = [];
	// public $url = [];

	public static function session_start(){
		session_start();
		static::check_login();
		static::check_message();
		// static::check_vars();
		static::check_url();
	}

	public static function is_logged_in(){
		//
		return static::$logged_id;
	}


	public static function set($key, $value) {
		// save value in SESSION array
		$_SESSION[$key] = $value;
	}

	public static function get($key) {
		// save value in SESSION array
		if( isset($_SESSION[$key])) {
			return $_SESSION[$key];
		} else {
			return null;
		}
	}

	public static function delete($key) {
		// save value in SESSION array
		unset($_SESSION[$key]);
	}


	public static function login($user) {
		// database should find user based on username/password
		if($user){
			static::$user_id = $_SESSION['user_id'] = $user->ID;
			static::$logged_id = true;
		}
	}

	public static function logout() {
		unset($_SESSION['user_id']);
		static::$user_id = null;
		static::$logged_id = false;
	}

	public static function message($mess=[]){
		// if called with argument , it sets the message
		// if called without, we're getting message and 
		// reseting it to ''
		// useful with header() ...
		if(!empty($mess)) {
			$_SESSION['message'] = $mess; // important
			static::$message = $mess;
			return true;
		} else {
			// reset message - it's been read
			$tmp = static::$message;
			static::$message = [];
			unset($_SESSION['message']);
			return $tmp;
		}
	}

	// private methods
	private static function check_login() {
		if(isset($_SESSION['user_id'])) {
			static::$user_id = $_SESSION['user_id'];
			static::$logged_id = true;
		} else {
			static::$user_id = null;
			static::$logged_id=false;
		}
	}

	private static function check_message() {
		if(isset($_SESSION['message'])) {
			static::$message = $_SESSION['message'];
		} else {
			static::$message = [];
		}
	}

	private static function check_vars() {
		if(isset($_SESSION['vars'])) {
			static::$vars = $_SESSION['vars'];
		} else {
			static::$vars = [];
		}
	}

	private static function check_url() {
		if(isset($_SESSION['url'])) {
			static::$url = $_SESSION['url'];
		} else {
			static::$url = [];
		}
		$to_add = $_SERVER['QUERY_STRING'];
      if( preg_match('/\.ico$/', $to_add) ) { return true;}
      if( preg_match('/\.css$/', $to_add) ) { return true;}
      if( preg_match('/\.js$/', $to_add) ) { return true;}
      if( preg_match('/\.map$/', $to_add) ) { return true;}
		array_unshift(static::$url , $to_add);

		// keep just 10 newest elements in array
		$_SESSION['url'] = array_slice(static::$url, 0, 10);
		static::$url = $_SESSION['url'];
	}

	// public methods
	public static function get_current_url(){
		$url = static::$url;
		return $url[0];
	}

	public static function get_latest_url() {
		// latest url <> current_url
		$current_url = static::$url[0];
		foreach (static::$url as $url) {
			if($url != $current_url){
				return $url;
			}
		}
		return $current_url;
	}

	public static function get_latest_url_not_like($not_like) {
		$current_url = static::$url[0];
		foreach (static::$url as $url) {
			if($url != $current_url and !(strpos($url, $not_like) !== false) ){
				return $url;
			}
		}
		return '';
	}

	public static function get_latest_url_like($like) {
		$current_url = static::$url[0];
		foreach (static::$url as $url) {
			if($url != $current_url and (strpos($url, $like) !== false) ){
				return $url;
			}
		}
		return '';
	}


}
// $var = 100;
// $session = new Session();

?>