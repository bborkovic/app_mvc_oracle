<?php 

namespace App\Models;

use Core\Model;

class Book extends Model {
	
	// table the class is related
	public static $table_name = "books";
	public static $db_fields = array(
			  'id' // primary
			, 'author_id', 'publisher_id', 'category_id' // foreign keys
			, 'name', 'short_info', 'about_book', 'about_authors' , 'book_photo'
		); // attributes
	public static $primary_keys = array('id', 'author_id', 'publisher_id', 'category_id');
	
	public $validations = array(
		"author_id" => array(
			"type" => "drop",
			"label" => "Author Name",
			),
		"publisher_id" => array(
			"type" => "drop",
			"label" => "Publisher Name",
			),
		"category_id" => array(
			"type" => "drop",
			"label" => "Category Name",
			),		
		"name" => array(
			"type" => "text",
			"label" => "Book Name",
			"required" => true,
			// "regexp" => '/\d+\.\d+/',
			),
		"short_info" => array(
			"type" => "text",
			"label" => "Short Info",
			),
		"about_book" => array(
			"type" => "text",
			"label" => "About Book",
			),
		"about_book" => array(
			"type" => "text",
			"label" => "About Book",
			),
		"about_authors" => array(
			"type" => "text",
			"label" => "About Author",
			),
		"book_photo" => array(
			"type" => "text",
			"label" => "Photo file name",
			),
	);

	public $children = [
		// 'Book' => 
		// 	['table_name' => 'books', 'foreign_key' => 'author_id'],
		];

	public $parents = [		
		'Author' => 
			['table_name' => 'authors', 'foreign_key' => 'author_id'],
		];

	// fields
	public $id;
	public $author_id;
	public $publisher_id;
	public $category_id;
	public $name;
	public $short_info;
	public $about_book;
	public $about_author;
	public $book_photo;

} // End of Class


?>