<?php 

namespace App\Models;

use Core\ModelOracle;
use Core\Session;

class User extends ModelOracle {
	
	// table the class is related
	public static $table_name = "webusers";
	protected static $db_fields = array('ID','USERNAME','PASSWORD','FIRST_NAME','LAST_NAME');
	// columns of table users
	
	// public $children = array(
	// 	'Ad' => array( // Class Name
	// 		'table_name' => 'ads',
	// 		'foreign_key' => 'user_id'
	// 		),
	// 	'children2' => array( // This is class Name
	// 		'table_name' => 'table_name',
	// 		'foreign_key' => 'key_name'
	// 		)
	// 	);

	// public $parents = array(
	// 	);

	public $ID;
	public $USERNAME;
	public $PASSWORD;
	public $FIRST_NAME;
	public $LAST_NAME;

	public $validations = array(
		"USERNAME" => array(
			"type" => "text",
			"label" => "Username",
			"rule" => "alphaNumeric",
			"required" => true,
			"allowEmpty" => false,
			"maxlength" => 20,
			"minlength" => 5,
			"message" => "Username is not correct"
			),
		"PASSWORD" => array(
			"type" => "password",
			"label" => "Password",
			"rule" => "alphaNumeric",
			"required" => true,
			"allowEmpty" => false,
			"maxlength" => 20,
			"minlength" => 5,
			"message" => "Password is not correct"
			),
		"first_name" => array(
			"type" => "text",
			"label" => "First Name",
			"rule" => "alphaNumeric",
			"required" => false,
			"allowEmpty" => false,
			"maxlength" => 20,
			"minlength" => 5,
			"message" => "First name is not correct"
			),
		"last_name" => array(
			"label" => "Last Name",
			"rule" => "alphaNumeric",
			"required" => false,
			"allowEmpty" => false,
			"maxlength" => 20,
			"minlength" => 5,
			"message" => "Last name is not correct"
			),
	);

   public static function authenticate($username="", $password="") {
		//global $database;
		$database = static::getDB();
		// $username = $database->escape_value($username);
		// $password = $database->escape_value($password);
		$sql =  " select * from " . self::$table_name;
		$sql .= " where username = :username ";
		$sql .= " and password = :password ";

		$result_array = self::find_by_sql($sql , [":username"=>$username , ":password"=>$password]);
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public function full_name() {
		// returns full name if there is instance
		return $this->first_name . " " . $this->last_name;
	}

	public static function get_logged_username() {
		if(Session::is_logged_in()){
			$user = User::find_by_id(Session::$user_id);
			return $user->USERNAME;
		} else {
			return "";
		}
	}

} // End of Class


?>