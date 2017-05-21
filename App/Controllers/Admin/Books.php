<?php 

namespace App\Controllers\Admin;

use Core\View;
	use Core\Form;
	use Core\Util;
	use Core\Session;
	use Core\Paginator;
	use Core\Upload;
	use App\Config;
	use App\Models\User;
	use App\Models\Publisher;
	use App\Models\Author;
	use App\Models\Category;
	use App\Models\Book;

class Books extends \Core\Controller {
	
	public function indexAction() {
		// get list of books in paginated form
		// and process search field if submitted
		$per_page = 5;
		$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
		$search_query = (isset($_GET['search'])) ? $_GET['search'] : '%';
		$search = (isset($_GET['search'])) ? $_GET['search'] : '';

		$total_count = Book::count_by_sql("select count(*) from books where name like '%" . $search_query . "%'");
		$paginator = new Paginator($page, $per_page, $total_count);
		$paginator->url_params = ( empty($search) ) ? [] : [ "search" => $search ];
		$paginator->page_url = '/admin/books/index';
		$books = $paginator->getModelData("Book" , "where lower(name) like '%" . $search_query . "%'");

		View::renderTemplate('Admin/Books/index.html', array(
				"books" => $books,
				"paginator" => $paginator,
				"messages" => $this->get_messages(),
				"search" => $search,
			)
		);
	}

	public function addNewAction() {
		
		$parent_ids = $this->get_parent_ids();

		$form = new Form("Book", array(
			"author_id", "publisher_id", "category_id", 
			"name", "short_info", "about_book", "about_authors"
				)
			);
		$form->button_value = "Create";
		$form->setFieldsSelect( "author_id" , $parent_ids["authors_hash"] );
		$form->setFieldsSelect( "publisher_id" , $parent_ids["publishers_hash"] );
		$form->setFieldsSelect( "category_id" , $parent_ids["categories_hash"] );

		if(isset($_POST['submit'])) {
			$publisher = $form->parsePost($_POST, true); // get post from parsed $_POST
			
			if( $form->has_validation_errors() ){
				Session::message(["Validation errors!" , "error"]);
			} else {
				if($publisher->create()) {
					Session::message(["Book saved!" , "success"]);
					redirect_to( $book->id . '/edit');
				} else {
					// Exception will be thrown
				}
			}
		} 
		// // render view
		View::renderTemplate('Admin/Books/new.html', array(
			"form" => $form,
			"messages" => $this->get_messages(),
			) 
		);
	}

	public function editAction() {
		
		$parent_ids = $this->get_parent_ids();
		$book = Book::find_by_id( $this->route_params['id'] );
		
		$form = new Form($book, array(
			"author_id", "publisher_id", "category_id", 
			"name", "short_info", "about_book", "about_authors"
				)
			);
		$form->button_value = "Update";
		$form->setFieldsSelect( "author_id" , $parent_ids["authors_hash"] );
		$form->setFieldsSelect( "publisher_id" , $parent_ids["publishers_hash"] );
		$form->setFieldsSelect( "category_id" , $parent_ids["categories_hash"] );

		if(isset($_POST['submit'])) {
			$publisher = $form->parsePost($_POST, true); // get post from parsed $_POST
			
			if( $form->has_validation_errors() ){
				Session::message(["Validation errors!" , "error"]);
			} else {
				if($book->update()) {
					Session::message(["Book saved!" , "success"]);
				} else {}
			}
		} 
		// // render view
		View::renderTemplate('Admin/Books/edit.html', array(
			"form" => $form,
			"messages" => $this->get_messages(),
			) 
		);
	}

	public function deleteAction() {}

	public function uploadAction() {
		
		$book = Book::find_by_id( $this->route_params['id'] );
		$form = new Form($book, array( "book_photo" ));
		$form->button_value = "Save Photo to Book";
		
		
		if(isset($_POST['submit'])) {
			$book = $form->parsePost($_POST, true); // get post from parsed $_POST
			
			if( $form->has_validation_errors() ){
				Session::message(["Validation errors!" , "error"]);
			} else {
				if($book->update()) {
					Session::message(["Book saved!" , "success"]);
				} else {}
			}
		}

		if(isset($_POST['upload'])) {
			$destination = Config::SITE_ROOT . '/uploads/books';
			$upload = new Upload($destination);
			$upload->set_max_size(1000000);
			$upload->upload();
			$result = $upload->get_messages();
			if( strpos( $result[0] , "was uploaded successfully") ) {
				Session::message( [ 'Image was uploaded!<br/>File name = "' . $upload->getName() . '"', "success"] );
				// if success, add file_name to form for save
				$form->model_class->book_photo = $upload->getName();
			} else {
				Session::message( [ join(', ', $result) , "error"] );
			}
		}


		View::renderTemplate('Admin/Books/upload.html', array(
			"form" => $form,
			"book" => $book,
			"messages" => $this->get_messages(),
			)
		);
	}

	protected function before() {
		if( !Session::is_logged_in()){
			redirect_to('/users/login');
		}
	}

	protected function after() {}

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
		$array_of_messages["page_title"] = "Admin/Books";
		$array_of_messages["sidebar"] = $this->get_sidebar();
		$array_of_messages["latest_index_url"] = '/' . Session::get_latest_url_like("books/index");
		return $array_of_messages;
	}

	private function get_parent_ids() {
		$return_array = array();
		$authors = Author::find_all();
		$return_array["authors_hash"] = Util::obj2hash( $authors , "id", "getFullName");
		
		$publishers = Publisher::find_all();
		$return_array["publishers_hash"] = Util::obj2hash( $publishers, "id", "name");
		
		$categories = Category::find_all();
		$return_array["categories_hash"] = Util::obj2hash( $categories , "id", "name");

		return $return_array;
	}

}

?>