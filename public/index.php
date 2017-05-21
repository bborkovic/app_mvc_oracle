<?php

	error_reporting(E_ALL ^ E_DEPRECATED);
	ini_set('display_errors', 1);

	require_once '../Core/initialize.php';

	Core\Session::session_start();

	set_error_handler('Core\Error::errorHandler');
	set_exception_handler('Core\Error::exceptionHandler');

	// $session = new Core\Session();
	$router = new Core\Router();
	$url = $_SERVER['QUERY_STRING'];

	# fix redirects

	$router->add('{controller}/{action}');
	$router->add('{controller}/{id:\d+}/{action}');
	$router->add('admin/{controller}/{action}', ['namespace'=>'Admin'] );
	$router->add('admin/{controller}/{id:\d+}/{action}', ['namespace'=>'Admin'] );

	# !! Run controllers that are needed
	$router->dispatch($url);


?>




