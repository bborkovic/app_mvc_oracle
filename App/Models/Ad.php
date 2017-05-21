<?php 

namespace App\Models;

use Core\Model;

class Ad extends Model {
	
	// table the class is related
	public static $table_name = "ads";
	public static $db_fields = array('id','user_id','title');
	public static $primary_keys = array('id');
	// columns of table users
	
	public $validations = array(
		"name" => array(
			"type" => "text",
			"label" => "Name",
			"rule" => "alphaNumeric",
			"required" => true,
			"allowEmpty" => false,
			"maxlength" => 20,
			"minlength" => 5,
			"message" => "Name is not set"
			),
		"details" => array(
			"type" => "text",
			"label" => "Details",
			"rule" => "alphaNumeric",
			"required" => true,
			"allowEmpty" => false,
			"maxlength" => 20,
			"minlength" => 5,
			"message" => "Name is not set"
			),
	);

	public $children = array(
		'Ad' => array( // Class Name
			'table_name' => 'ads',
			'foreign_key' => 'user_id'
			),
		'children2' => array( // This is class Name
			'table_name' => 'table_name',
			'foreign_key' => 'key_name'
			)
		);

	public $parents = array(
		);

	public $id;
	public $title;



} // End of Class


?>