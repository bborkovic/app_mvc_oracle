<?php
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set('display_errors', 1);

spl_autoload_register( function($class) {
	$root = dirname(__DIR__); // get the parent directory
	$file = $root . '/' . str_replace('\\','/', $class) . '.php'; // get php file of class
	if( is_readable($file)) {
		require $file;
	}
	// add the Vendor Folder to autoload
	// works if class name = name of php file
	$file = $root . '/' . 'Vendor/' . str_replace('\\','/', $class) . '.php';
	if( is_readable($file)) {
		require $file;
	}
});
?>


<?php
	
// 	use Imagine\Gd\Imagine;
	$imagine = new Imagine\Gd\Imagine();
	// make an empty image (canvas) 120x160px
	$collage = $imagine->create(new Imagine\Image\Box(4*100, 4*100));
	
	// starting coordinates (in pixels) for inserting the first image
	$x = 0;
	$y = 0;
	
	foreach (glob('uploads/auti/*.jpg') as $path) {
		// open photo
		$photo = $imagine->open($path);
		$photo->resize(new Imagine\Image\Box(100, 100));
		// paste photo at current position
		$collage->paste($photo, new Imagine\Image\Point($x, $y));
	
		// move position by 30px to the right
		$x += 100;
	
		if ($x >= 100*4) {
			// we reached the right border of our collage, so advance to the
			// next row and reset our column to the left.
			$y += 100;
			$x = 0;
		}
	
		if ($y >= 100*4) {
			break; // done
		}
	}
	
	$collage->save('uploads/auti/collage.jpg');

?>