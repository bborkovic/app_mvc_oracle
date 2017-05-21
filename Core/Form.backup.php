<?php 

namespace Core;

class Form {

	public $model_class_name;
	public $model_class;
	public $fields;
	public $method;
	public $action;
	public $validations;
	public $field_types = array(
		"alphaNumeric" => "text"
		);
	
	public $validation_errors = array();

	function __construct($class_name, $fields=[]){
		if(is_object($class_name)) {
			$this->model_class = $class_name;
			$this->model_class_name = get_class($class_name);
		} else {
			$model = "App\Models\\$class_name";
			$this->model_class = new $model;
			$this->model_class_name = $class_name;
		}
		// if no fields are provided, take all db_fields from Model Class
		if(empty($fields)){
			$class = $this->model_class_name;
			$this->fields = array_diff( $class::$db_fields, $class::$primary_keys );
		} else {
			$this->fields = $fields;
		}
		// get validations from Model validations array
		$this->validations = $this->model_class->validations;
	}

	public function render() {

		$return_html = "";

		$this->render_form_begin();

		foreach ($this->fields as $field) {

			if( isset($this->model_class->$field)) {
				$value = $this->model_class->$field;
			} else {
				$value = "";
			}
			$this->render_form_element($field, $value);
		}
		$this->render_button();
		$this->render_form_end();
	}

	public function render_form_begin(){

		echo "<form role=\"form\" action=\"{$this->action}\" method=\"{$this->method}\">";
	}

	public function render_form_end(){
		//
		echo "</form>";
	}

	public function render_button(){
		//
		echo "<button type=\"submit\" class=\"btn btn-default\" name=\"submit\" value=\"Upload\">Save</button>";
	}

	public function render_form_element($field, $value) {
		if( isset($this->validations[$field]["label"]) ) {
			$label = $this->validations[$field]["label"];
		} else {
			$label = "No label";
		}

		if( isset($this->validations[$field]["type"]) ) {
			$type = $this->validations[$field]["type"];
		} else {
			$type = "text";
		}

		if (array_key_exists ($field , $this->validation_errors)){
			$error_text = $this->validation_errors[$field];
			echo "<div class=\"form-group has-error has-feedback\">";
			echo "<label for=\"{$field}\">{$label}</label>";
			echo "<label class=\"text-danger\" for=\"{$field}\">&nbsp&nbsp**&nbsp{$error_text}</label>";
			echo "<input type=\"{$type}\" class=\"form-control\" name=\"{$field}\" value=\"{$value}\"/>";
			// echo "<span class=\"glyphicon glyphicon-remove form-control-feedback\"></span>";
			echo "</div>";
		} else {
			echo "<div class=\"form-group\">";
			echo "<label for=\"{$field}\">{$label}</label>";
			echo "<input type=\"{$type}\" class=\"form-control\" name=\"{$field}\" value=\"{$value}\"/>";
			echo "</div>";
		}
	}

	// populate class with POST elements
	public function parsePost($post_array) {
		foreach ($this->fields as $field) {
			$this->model_class->$field = $post_array[$field];
			# Validate field according to rules
			# And populate validation_array if found errors
			$this->validate_field($field, $this->model_class->$field);
		}
		return $this->model_class;
	}

	public function has_validation_errors() {
		# Comment
		return empty($this->validation_errors) ? false : true;
	}

	public function validate_field( $field, $value) {
		$validation_rules = $this->validations[$field];

		if( array_key_exists("allowEmpty", $validation_rules) ){
			if ( strlen($value) == 0 and !$validation_rules["allowEmpty"] ){
				$this->validation_errors[$field] = "Not Allow Empty!";
				return;
			}
		}

		# Check length
		if( array_key_exists("minlength", $validation_rules) ){
			if ( strlen($value) < $validation_rules['minlength'] ) {
				$this->validation_errors[$field] = "Lenght should be > " . $validation_rules['minlength'];
				return;
			}
		}

		if( array_key_exists("maxlength", $validation_rules) ){
			if ( strlen($value) > $validation_rules['maxlength'] ) {
				$this->validation_errors[$field] = "Lenght should be < " . $validation_rules['maxlength'];
				return;
			}
		}
	}


}



?>