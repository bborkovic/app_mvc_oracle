<?php 

namespace Core;

class Form {

	// name of the class
	public $model_class_name;

	// object
	public $model_class;

	// array, list of fields to display in form
	private $fields;

	// array or fields of type select [ "cust_id"=>[ "value1"=>"display value1" , "value2"=>"display value2" ] ]
	protected $fields_select = [];

	// array or fields of type radio [ "cust_id"=>[ "value1"=>"display value1" , "value2"=>"display value2" ] ]
	protected $fields_radio = [];

	protected $model_namespace = 'App\\Models\\';

	// form method, post or get
	protected $method = "post";

	// link to form action
	public $action;

	public $button_value = "Save";

	// ?
	public $validations;
	public $validate_fields = true;

	// ?
	public $field_types = array(
		"alphaNumeric" => "text"
		);

	// validation errors, should be empty to insert/update
	public $validation_errors = array();

	function __construct($class_name, $fields=[]){
		if(is_object($class_name)) {
		    // if it's object use it
			$this->model_class = $class_name;
			$this->model_class_name = get_class($class_name);
		} else {
            // instantiate new Model
			$model = $this->model_namespace . $class_name;
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

	public function setFieldValue($field, $value) {
		$this->model_class->$field = $value;
	}


	public function setFieldsRadio($fields_radio) {
		//
		$this->fields_radio[] = $fields_radio;
	}

	public function setFieldsSelect($field_name, $field_values) {
		//
		$this->fields_select[$field_name] = $field_values;
	}

	public function render() {
		$return_html = "";

		$return_html .= $this->render_form_begin();

		foreach ($this->fields as $field) {
			if( isset($this->model_class->$field)) {
				$value = $this->model_class->$field;
			} else {
				$value = "";
			}

			if(isset($this->fields_select[$field])) {
				$return_html .= $this->render_form_element_select($field, $value);
			} else {
				$return_html .= $this->render_form_element($field, $value);
			}
		}
		$return_html .= $this->render_button();
		$return_html .= $this->render_form_end();
		return $return_html;
	}

	public function render_form_begin(){
		//
		return '<form role="form" action="' . $this->action . '" method="' . $this->method . '">';
	}

	public function render_form_end(){
		//
		return '</form>';
	}

	public function render_button(){
		//
		return '<button type="submit" class="btn btn-primary" name="submit" value="Submit">' . $this->button_value . '</button>';
	}

	public function render_form_element_select($field, $value) {
		if( isset($this->validations[$field]["label"])) {
			$label = "Select " . $this->validations[$field]["label"];
		} else {
			$label = "Select " . $field;
		}
		
		$ret_text = "";
		$ret_text .= '<div class="form-group">';
		$ret_text .= '<label for="select">' . $label . '</label>';
		$ret_text .= '<select class="form-control" id="select" name="' . $field . '">';
		$have_selected = 0;
		foreach ($this->fields_select[$field] as $db_id => $display_value) {
			if( $value == $db_id ){
				$selected = "selected";
				$have_selected += 1;
			} else {
				$selected = "";
			}
			$ret_text .= '<option value="' . $db_id . '"' . $selected . '>' . $display_value . '</option>';
		}
		if( $have_selected == 0 ) { // add empty field
			$ret_text .= '<option hidden selected></option>';
		}
		
		$ret_text .= '</select>';
		$ret_text .= '</div>';
		return $ret_text;
	}

	public function render_form_element($field, $value) {
		$ret_text = '';
		if( isset($this->validations[$field]["label"]) ) {
			$label = $this->validations[$field]["label"];
		} else {
			$label = $field;
		}

		if( isset($this->validations[$field]["type"]) ) {
			$type = $this->validations[$field]["type"];
		} else {
			$type = "text";
		}

		// depends if there is an error


		if (array_key_exists ($field , $this->validation_errors)){
			$error_text = $this->validation_errors[$field];
			$ret_text .= '<div class="form-group has-error has-feedback">';
			$ret_text .= '<label for="' . $field . '">' . $label . '</label>';
			$ret_text .= '<label class="text-danger" for="' . $field . '">&nbsp&nbsp**&nbsp' . $error_text . '</label>"';
			$ret_text .= '<input type="' . $type . '" class="form-control" name="' . $field . '" value="' . $value . '"/>';
			$ret_text .= "</div>";
		} else {
			$ret_text .= '<div class="form-group">';
			$ret_text .= '<label for="' . $field . '">' . $label . '</label>';
			// $ret_text .= '<input type="' . $type . '" class="form-control" name="' . $field . '" value="' . $value . '"/>';
			$ret_text .= '<input type="' . $type . '" class="form-control" name="' . $field . '" value="' . $value . '"' . ' placeholder=""' . '/>';
			$ret_text .= '</div>';
		}
		return $ret_text;
	}

	// populate class with POST elements
	public function parsePost($post_array) {
		foreach ($this->fields as $field) {
			$this->model_class->$field = $post_array[$field];
			# Validate field according to rules
			# And populate validation_array if found errors
			if($this->validate_fields) { // validate if it's been set this way
				$this->validate_field($field, $this->model_class->$field);
			}
		}
		return $this->model_class;
	}

	public function has_validation_errors() {
		# Comment
		return empty($this->validation_errors) ? false : true;
	}

	private function add_validation_error($field, $error_text){
		if(isset($this->validation_errors[$field])) {
			// append
			$this->validation_errors[$field] .= ", " . $error_text;
		} else {
			$this->validation_errors[$field] = $error_text;
		}
	}

	public function getValidationError($field) {
		if ( isset($this->getValidation[$field])) {
			return $this->getValidation[$field];
		} else {
			return false;
		}
	}
	

	public function validate_field( $field, $value) {

		if(isset( $this->validations[$field]["label"] )){
			$label = $this->validations[$field]["label"];
		} else { 
			$label = $field;
		}

		if( isset($this->validations[$field])) {
			$validation_rules = $this->validations[$field];
		} else { return; }

		// loop through rules , and valideate value if defined to do so
		// exit on first error, display only one value
		foreach ($validation_rules as $rule => $rule_value) {

			// validating "required"
			if($rule === 'required'){
				if(empty($value)) {
					$this->add_validation_error($field, 'field "' . $label . '"' . " is required");
					return; // additional checks not required!!
				}
			}

			// validating "minlenght"
			if( $rule == 'minlength' && strlen($value) < $rule_value ) {
				$this->add_validation_error($field, 'field "' . $field . '"' . " length should be >= " . $rule_value);
				return;
			}

			// validating "maxlenght"
			if( $rule == 'maxlength' && strlen($value) > $rule_value ) {
				$this->add_validation_error($field, 'field "' . $field . '"' . " length should be <= " . $rule_value);
				return;
			}

			// validating "regexp"
			if( $rule == 'regexp' && !preg_match($rule_value, $value) ) {
				$this->add_validation_error($field, 'field "' . $field . '"' . " not matches {$rule_value}" );
				return;
			}



		}

	// if( array_key_exists("allowEmpty", $validation_rules) ){
		// 	if ( strlen($value) == 0 and !$validation_rules["allowEmpty"] ){
		// 		$this->validation_errors[$field] = "Not Allow Empty!";
		// 		return;
		// 	}
		// }

		// # Check length
		// if( array_key_exists("minlength", $validation_rules) ){
		// 	if ( strlen($value) < $validation_rules['minlength'] ) {
		// 		$this->validation_errors[$field] = "Lenght should be > " . $validation_rules['minlength'];
		// 		return;
		// 	}
		// }

		// if( array_key_exists("maxlength", $validation_rules) ){
		// 	if ( strlen($value) > $validation_rules['maxlength'] ) {
		// 		$this->validation_errors[$field] = "Lenght should be < " . $validation_rules['maxlength'];
		// 		return;
		// 	}
		// }
	}


	private function surround_qq($text) {
		return '"' . $text . '"';
	}

}



?>