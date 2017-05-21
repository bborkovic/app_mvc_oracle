<?php 

namespace Core;
// store just user_id in session

class Session {

	private $logged_id = false;
	public $user_id; // if it's logged in
	public $message;
	public $errors = [];
	public $vars = [];
	public $url = [];

	function __construct(){
		session_start();
		$this->check_message();
		$this->check_login();
		$this->check_vars();
		$this->check_url();
	}

	public function is_logged_in(){
		//
		return $this->logged_id;
	}

	public function say_hello($out = "hello"){
		//
		echo $out;
	}

	public function login($user) {
		// database should find user based on username/password
		if($user){
			$this->user_id = $_SESSION['user_id'] = $user->id;
			$this->logged_id = true;
		}
	}

	public function logout() {
		unset($_SESSION['user_id']);
		unset($this->user_id);
		$this->logged_id = false;
	}

	public function message($mess=[]){
		if(!empty($mess)) {
			$_SESSION['message'] = $mess; // important
			$this->message = $mess;
			return true;
		} else {
			// reset message - it's been read
			$tmp = $this->message;
			$this->message = [];
			unset($_SESSION['message']);
			return $tmp;
			
		}
	}

	public function save_error($error, $module="") {
		array_push($this->errors , "Error: {$error}, Module: {$module}");
	}

	public function get_errors() {
		if( !empty($this->errors) ) {
			return join("; " , $this->errors);
		} else {
			return "";
		}
	}

	// private methods
	private function check_login() {
		if(isset($_SESSION['user_id'])) {
			$this->user_id = $_SESSION['user_id'];
			$this->logged_id = true;
		} else {
			unset($this->user_id);
			$this->logged_id=false;
		}
	}

	private function check_message() {
		if(isset($_SESSION['message'])) {
			$this->message = $_SESSION['message'];
		} else {
			$this->message = [];
		}
	}

	private function check_vars() {
		if(isset($_SESSION['vars'])) {
			$this->vars = $_SESSION['vars'];
		} else {
			$this->vars = [];
		}
	}

	private function check_url() {
		if(isset($_SESSION['url'])) {
			$this->url = $_SESSION['url'];
		} else {
			$this->url = [];
		}
		$to_add = $_SERVER['QUERY_STRING'];
		if($to_add == "favicon.ico") { return true;}
		array_unshift($this->url , $to_add);

		// keep just 10 newest elements in array
		$_SESSION['url'] = array_slice($this->url, 0, 10);
		$this->url = $_SESSION['url'];
	}

	public function get_current_url() {
		return $this->url[0];
	}

	public function get_latest_url() {
		$current_url = $this->url[0];
		foreach ($this->url as $url) {
			if($url != $current_url){
				return $url;
			}
		}
		return $current_url;
	}


}

$session = new Session();

?>