<?php 

namespace App\Models;

use Core\Model;

class Category extends Model {
	
	// table the class is related
	public static $table_name = "categories";
	public static $db_fields = array('id', 'name');
	public static $primary_keys = array('id');
	
	public $validations = array(
		"name" => array(
			"type" => "text",
			"label" => "Name",
			"required" => true,
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


} // End of Class


?>