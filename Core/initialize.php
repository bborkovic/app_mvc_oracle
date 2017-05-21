<?php 

	require_once 'functions.php';

	require_once dirname(__DIR__) . '/Vendor/Twig/lib/Twig/Autoloader.php';
	Twig_Autoloader::register();


	spl_autoload_register( function($class) {
		$root = dirname(__DIR__); // get the parent directory
		$file = $root . '/' . str_replace('\\','/', $class) . '.php'; // get php file of class
		if( is_readable($file)) {
			require $file;
			// require $root . '/' . str_replace('\\','/', $class) . '.php';
		}
		// add the Vendor Folder to autoload
		// works if class name = name of php file
		$file = $root . '/' . 'Vendor/' . str_replace('\\','/', $class) . '.php';
		if( is_readable($file)) {
			require $file;
		}


	});






?>