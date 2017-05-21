<?php 

namespace App\Controllers;

use Core\View;
	use Core\Form;
	use Core\Util;
	use Core\Upload;
	use Core\Session;
	use App\Config;
	use App\Models\User;
	use Core\Paginator;

class Test extends \Core\Controller {
	
	public function indexAction() {

      $this->messages["page_title"] = "Test/index";

      View::renderTemplate('Test/index.html', array(
         "messages" => $this->messages,
         ) 
      );



	}


   public function before() {
      $this->messages = [];
      $this->messages["username"] = User::get_logged_username();
      $this->messages["message"] = get_message();
   }


}

?>