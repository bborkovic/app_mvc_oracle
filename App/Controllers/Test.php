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
      echo "<br/>";
      echo "I'm in Test/index action";
      // $this->messages["page_title"] = "Test/index";

      // View::renderTemplate('Test/index.html', array(
      //    "messages" => $this->messages,
      //    ) 
      // );



	}


   public function before() {
      echo "before action in Test controller is called ";
   }


}

?>