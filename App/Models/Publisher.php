<?php 

namespace App\Models;

use Core\Model;

class Publisher extends Model {
	
	// table the class is related
	public static $table_name = "publishers";
	public static $db_fields = array('id', 'name', 'about');
	public static $primary_keys = array('id');
	
	public $validations = array(
		"name" => array(
			"type" => "text",
			"label" => "Name",
			"rule" => "alphaNumeric",
			"required" => true,
			"allowEmpty" => false,
			"maxlength" => 20,
			"minlength" => 3,
			"message" => "Name is not set"
		),
		"about" => array(
			"type" => "text",
			"label" => "About",
			"rule" => "alphaNumeric",
			"required" => true,
			"allowEmpty" => false,
			"maxlength" => 60,
			"minlength" => 3,
			"message" => "Name is not set"
		),
	);

	public $children = [
		'Book' => 
			['table_name' => 'books', 'foreign_key' => 'publisher_id'],
		];

	public $parents = [];

	// fields
	public $id;
	public $name;
	public $about;

} // End of Class


?>