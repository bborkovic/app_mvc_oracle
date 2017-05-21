<?php 

namespace App\Controllers\Admin;

class Users extends \Core\Controller {
	
	public function indexAction() {
		message("<br/>Hello from Controller: " . get_class($this) . ", Action: index()<br/>", "success");
	}

	protected function after() {
		echo "(after)";
	}

	protected function before() {
		echo "(before)";
	}

}

?>