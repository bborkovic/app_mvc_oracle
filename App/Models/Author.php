<?php 

namespace App\Models;

use Core\Model;

class Author extends Model {
	
	// table the class is related
	public static $table_name = "authors";
	public static $db_fields = array('id', 'first_name', 'last_name', 'about');
	public static $primary_keys = array('id');
	
	public $validations = array(
		"first_name" => array(
			"type" => "text",
			"label" => "First Name",
			"rule" => "alphaNumeric",
			"required" => true,
			"allowEmpty" => false,
			"maxlength" => 20,
			"minlength" => 2,
			"message" => "Name is not set"
			),
		"last_name" => array(
			"type" => "text",
			"label" => "Last Name",
			"rule" => "alphaNumeric",
			"required" => true,
			"allowEmpty" => false,
			"maxlength" => 20,
			"minlength" => 2,
			"message" => "Name is not set"
			),
	);

	public $children = [
		'Book' => 
			['table_name' => 'books', 'foreign_key' => 'author_id'],
		];

	public $parents = [];

	// fields
	public $id;
	public $first_name;
	public $last_name;
	public $about;

	public function getFullName() {
		return $this->first_name . " " . $this->last_name;
	}

} // End of Class


?>