<?php 

namespace App\Controllers;

use Core\View;
	use Core\Form;
	use Core\Util;
	use Core\Upload;
	use Core\Session;
	use App\Config;
	use App\Models\User;
	use App\Models\Book;
	use App\Models\Author;
	use Core\Paginator;

class Books extends \Core\Controller {
	
	public function indexAction() {

		// Session::message( array( "poruka", "success" ));

		$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
		$paginator = new Paginator($page, 8, Book::count_all() );
		$books = array_chunk( $paginator->getModelData("Book") , 4 );
		View::renderTemplate('Books/index.html', array(
				"books" => $books,
				"add_links" => ["Add new Book" => '/books/add-new'],
				"paginator" => $paginator,
				"message" => get_message(),
				"page_title" => "Book Index"
			)
		);
	}

	public function show() {
		$book = Book::find_by_id( $this->route_params['id'] );
		View::renderTemplate('Books/show.html', array(
				"book" => $book,
				"add_links" => [],
			)
		);
	}

	public function addNewAction() {
		
		$authors = Author::find_all();
		$authors_hash = Util::obj2hash( $authors , "id", "getFullName");

		$form = new Form("Book", [ "author_id", "name", "short_info", "about_book", "about_authors" ]);
		$form->action = "add-new";
		$form->setFieldsSelect( "author_id" , $authors_hash );

		if(isset($_POST['submit'])) {
			$book = $form->parsePost($_POST, true); // get book from parsed $_POST
			if($book->create()) {
				Session::message( ["New Book Created" , "success"]);
				redirect_to("/books/{$book->id}/edit");
			} else {
				// exception is thrown
			}
		} 
		// render view
		View::render('Books/add-new.php', [ 
				"form" => $form,
				"add_links" => []
			]);
	}

	public function editAction() {
		
		$authors = Author::find_all();
		$authors_hash = Util::obj2hash( $authors , "id", "getFullName");

		$book = Book::find_by_id( $this->route_params['id'] );
		$form = new Form( $book , [ "author_id", "name", "short_info", "about_book", "about_authors", "book_photo" ]);
		$form->action = "edit";
		$form->setFieldsSelect( "author_id" , $authors_hash );

		if(isset($_POST['submit'])) {
			$book = $form->parsePost($_POST);
			if($book->update()) {
				Session::message(["Book modified!" , "success"]);
			} else {
				// Exception will be thrown
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
		View::render('Books/edit.php', [ "form" => $form ] );
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
		if( !Session::is_logged_in() and $this->route_params['action'] != 'index'){
			Session::message( [ "You have login for this action!", "warning" ] );
			redirect_to('/users/login');
		}
	}

	protected function after() {
	}

}

?>