<?php

	error_reporting(E_ALL ^ E_DEPRECATED);
	ini_set('display_errors', 1);

	require_once '../Core/initialize.php';

	Core\Session::session_start();

	set_error_handler('Core\Error::errorHandler');
	set_exception_handler('Core\Error::exceptionHandler');

	use App\Models\Book;
	use Core\Form;


	$form = new Form("Book", array("name"));
	$form->validate_field("name" , "1231231232222");

	if( isset($form->validation_errors['name'])) {
		echo $form->getValidation['name'];
	}

	echo "End of script";

 ?>



