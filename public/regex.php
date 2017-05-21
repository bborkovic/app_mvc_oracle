<?php

// $route = '{controller}/{action}';
// echo "1. " . $route . "<br/>";

// $route = preg_replace('/\//' , '\\/', $route);
// echo "2. " . $route . "<br/>";

// $route = preg_replace('/\{([a-z]+)\}/' , '(?P&lt\1&gt[a-z-]+)', $route);
// echo "3. " . $route . "<br/>";

// $route = '/^' . $route . '$/';
// echo "4. " . $route . "<br/>";




$regex = '/^(?P<controller>[a-z-]+)\/(?P<action>[a-z-]+)$/';
$url = 'new/york';
if ( preg_match($regex, $url, $matches) ) {
	foreach( $matches as $k=>$v) {
		if(is_string($k)) {
			echo "$k -> $v" . "<br/>";
		}
	}
	echo "Ima";
} else {
	echo "Nema";
}



?>