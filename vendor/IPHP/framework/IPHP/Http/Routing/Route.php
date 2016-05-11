<?php
namespace IPHP\Http\Routing;

class Route extends AbstractRoute {
	protected $name;
	protected $callableByMethod;
	protected $controller;
	protected $controllerMethod;

	public function __construct ($name, $url, $callableByMethod, $controller, $controllerMethod, array $filters = []) {
		parent::__construct($url, $filters);
		$this->name = $name;
		$this->callableByMethod = $callableByMethod;
		$this->controller = $controller;
		$this->controllerMethod = $controllerMethod;
	}

	public function getName () {
		return $this->name;
	}	

	public function getController () {
		return $this->controller;
	}

	public function getControllerMethod () {
		return $this->controllerMethod;
	}

	public function match ($url, array $namedGroups = [], Router $router) {
		$results = [];
		
		$pattern = $this->routePatternFromUrl($namedGroups);

		if (preg_match('/^'. $pattern .'$/', $url, $results)) {
			$this->registerFilters($router);
			return new RouteMatch($url, $results, $this);
		}

		return NULL;	
	}
}