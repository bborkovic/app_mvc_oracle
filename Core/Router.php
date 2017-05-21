<?php 

namespace Core;

class Router {

	protected $routes = []; // These arefilled via add method
	protected $params = []; // These are filled via match method

	public function add($route, $in_params=[]) {
		// Escape forward slashes
		$route = preg_replace('/\//' , '\\/', $route);

		// Convert variables e.g. {controller}
		$route = preg_replace('/\{([a-z]+)\}/' , '(?P<\1>[a-z-]+)', $route);

		// Convert variables with custom regexp { like id: \d+}
		$route = preg_replace('/\{([a-z]+):([^\}]+)\}/' , '(?P<\1>\2)', $route);

		// Add start and end delimiters
		$route = '/^' . $route . '$/';
		$this->routes[$route] = $in_params;
	}

	public function getRoutes() {
		// return routes
		return $this->routes;
	}

	public function match($url, $in_params=[]) {
		foreach($this->routes as $route=>$value) {
			if ( preg_match($route, $url, $matches)) {
				// $params = [];
				$params = $value;
				foreach($matches as $k => $match){
					if(is_string($k)) {
						$params[$k] = $match;
					}
				}
				$this->params = $params;
				return true;
			}
		}
		return false;
	}

	public function getParams() {
		// return params
		return $this->params;
	}

	public function dispatch($url) {

		$url = $this->removeQueryStringVariables($url);

		if ( $this->match($url)) {
			$controller = $this->params['controller'];
			$controller = $this->convertToStudlyCaps($controller);
			// $controller = "App\Controllers\\$controller";
			$controller = $this->getNamespace() . $controller;

			if( class_exists($controller)) {
				$controller_object = new $controller($this->params);
				$action = $this->params['action'];	
				$action = $this->convertToCamelCase($action);
				if( is_callable([$controller_object , $action])) {
					$controller_object->$action();
				} else {
					// message("Error: Method $action ( in controller $controller not found", "error");
					throw new \Exception("Method $action ( in controller $controller not found )");
				} 
			} else {
				// message("Error: Controller class ' . $controller . ' not found", "error");
				throw new \Exception("Error: Controller class " . $controller . " not found");
			}
		} else {
			// message("Error: The URL $url does not match", "error");
			throw new \Exception("Error: The URL $url does not match");
		}
	}

	protected function convertToStudlyCaps($string){
		return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
	}

	protected function convertToCamelCase($string){
		return lcfirst($this->convertToStudlyCaps($string));
	}

	protected function removeQueryStringVariables($url) {
		if ( $url != '') {
			$parts = explode('&', $url, 2);
			if( strpos($parts[0], '=') == false) {
				$url = $parts[0];
			} else {
				$url = '';
			}
		}
		return $url;
	}

	protected function getNamespace() {
		$namespace = 'App\Controllers\\';
		if ( array_key_exists('namespace', $this->params)) {
			$namespace .= $this->params['namespace'] . '\\';
		}
		return $namespace;
	}

}

?>