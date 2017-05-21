<?php 

namespace App\Controllers;

use Core\View;
use Core\Form;
use Core\Session;
use App\Models\User;



class Users extends \Core\Controller {
	
	public function login() {
		// message("Hello from Controller: " . get_class($this) . ", Action: login()", "success");
		$message = "";
		if( isset($_POST['submit'])) {

			$username = htmlspecialchars( $_POST['username'] );
			$password = htmlspecialchars( $_POST['password'] );

			$found_user = User::authenticate( $username, $password );
			if($found_user) {
				Session::login($found_user);
				Session::message( array("You are logged in!","success") );
				redirect_to('/' . Session::get_latest_url_not_like('logout'));
            // redirect_to('/test/index');
			} else {
            echo "username/password not correct";
				Session::message( array("Username/password combination not correct!","error") );
			}

		} 
		$form = new Form("User", ["username", "password"]);
		$form->action = "/users/login";
		View::renderTemplate('Users/login.html', array(
			"form" => $form ,
			"message" => get_message(),
			) 
		);
	}

	public function logout() {
		Session::message(["Logout Complete" , "success"]);
		Session::logout();
		redirect_to( '/users/login' );
	}


	protected function after() {
	}

	protected function before() {
	}

}

?>