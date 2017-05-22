<?php 

namespace App\Controllers;

use Core\View;
use Core\Form;
use Core\Session;
use App\Models\User;



class Users extends \Core\Controller {
	
	public function loginAction() {

		View::renderTemplate('Users/login.html', array(
			"messages" => $this->messages,
			) 
		);
	}

   public function processloginAction() {
      if( isset($_POST['submit'])) {
         $username = htmlspecialchars( $_POST['username'] );
         $password = htmlspecialchars( $_POST['password'] );

         $found_user = User::authenticate( $username, $password );
         if($found_user) {
            Session::login($found_user);
            Session::message( array("You are logged in!","success") );
            redirect_to('/' . Session::get_latest_url_not_like( ['logout', 'login']));
         } else {
            Session::message( array("Username/password combination not correct!","error") );
            redirect_to('/users/login');
         }
      }
   }

	public function logout() {
		Session::message(["Logout Complete" , "success"]);
		Session::logout();
		redirect_to( '/users/login' );
	}


	protected function after() {
	}

   public function before() {
      $this->messages = [];
      $this->messages["username"] = User::get_logged_username();
      $this->messages["message"] = get_message();
   }

}

?>