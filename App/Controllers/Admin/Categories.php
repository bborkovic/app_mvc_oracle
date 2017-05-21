<?php 

namespace App\Controllers\Admin;

use Core\View;
use Core\Form;
use Core\Util;
use Core\Session;
use App\Models\User;
use App\Models\Category;


class Categories extends \Core\Controller {
	
	public function indexAction() {
		//
		// echo "Hello from Namespace: Admin, Controller: Categories, Action: index";

		$categories = Category::find_all();
		View::renderTemplate('Admin/Categories/index.html' , array(
			"categories" => $categories,
			"messages" => $this->get_messages(),
			)
		);
	}

	public function addNewAction() {
		
		$form = new Form("Category", ["name"]);
		$form->button_value = 'Create';

		if(isset($_POST['submit'])) {
			$author = $form->parsePost($_POST, true); // get post from parsed $_POST
			
			if( $form->has_validation_errors() ){
				Session::message(["Validation errors!" , "error"]);
			} else {
				if($author->create()) {
					Session::message(["Post saved!" , "success"]);
					redirect_to('index');
				} else {
					// Exception will be thrown
				}
			}
		} 
		// // render view
		View::renderTemplate('Admin/Categories/new.html', array(
			"form" => $form,
			"messages" => $this->get_messages(),
			) 
		);
	}

	public function editAction() {

		$author = Category::find_by_id( $this->route_params['id'] );
		$form = new Form($author, ["name"]);
		
		if(isset($_POST['submit'])) {
			$post = $form->parsePost($_POST, true);

			if( $form->has_validation_errors() ){
				Session::message(["Validation errors!" , "error"]);
			} else {
				if($post->update()) {
					Session::message(["Post saved!" , "success"]);
				}
			}
		}

		View::renderTemplate('Admin/Categories/edit.html', array(
			"form" => $form,
			"messages" => $this->get_messages(),
			) 
		);
	}

	public function deleteAction() {
		// $post = Post::find_by_id( $this->route_params['id'] );
		// if($post->delete()) {
		// 	Session::message(["Post <strong>$post->name</strong> deleted!" , "success"]);
		// 	redirect_to('/posts/index');
		// } else {
		// 	Session::message(["Error saving! " . $error->get_errors() , "success"]);
		// }
	}

	protected function before() {
		if( !Session::is_logged_in()){
			redirect_to('/users/login');
		}
	}

	protected function after() {
	}

	private function get_sidebar(){
		$html = "";
		$html .= '<div id="sidebar">';
		$html .= '<h3>Links:</h3>';
		$html .= '<ul class="list-group">';
		$html .= '<a href="/admin/authors/index"><li class="list-group-item">Authors</li></a>';
		$html .= '<a href="/admin/publishers/index"><li class="list-group-item">Publishers</li></a>';
		$html .= '<a href="/admin/categories/index"><li class="list-group-item">Categories</li></a>';
		$html .= '<a href="/admin/categories/index"><li class="list-group-item">Bla...</li></a>';
		$html .= '</ul>';
		$html .= '</div>';
		return $html;
	}

	private function get_messages() {
		$array_of_messages = [];
		$array_of_messages["username"] = User::get_logged_username();
		$array_of_messages["message"] = get_message();
		$array_of_messages["page_title"] = "Admin/Categories";
		$array_of_messages["sidebar"] = $this->get_sidebar();
		return $array_of_messages;
	}

}

?>