<?php
namespace IPHP\Http\Routing;

class Route extends AbstractRoute {
	protected $name;
	protected $callableByMethod;
	protected $controller;
	protected $controllerMethod;
	protected $collection = [];
	protected $routeMatch = null;
	protected $childRoute;
	protected $parentRoute;

	public function __construct ($name, $url, $callableByMethod, $controller, $controllerMethod, array $filters = []) {
		parent::__construct($url, $filters);
		$this->name = $name;
		$this->callableByMethod = $callableByMethod;
		$this->controller = $controller;
		$this->controllerMethod = $controllerMethod;
	}

	public function addCollection (array $collection) {
		$this->collection = array_merge($this->collection, $collection);

		return $this;
	}

	public function getName (): string {
		return $this->name;
	}	

	public function getController () {
		return $this->controller;
	}

	public function getControllerMethod () {
		return $this->controllerMethod;
	}

	public function getRouteMatch () {
		return $this->routeMatch;
	}

	public function setParent (Route $route) {
		$this->parentRoute = $route;

		$route->setChild($this);
	}

	public function setChild (Route $route) {
		$this->childRoute = $route;
	}

	public function getChild () {
		return $this->childRoute;
	}

	public function getParent () {
		return $this->parentRoute;
	}

	public function match ($url, array $namedGroups = [], Router $router) {
		$results = [];
		
		$pattern = $this->routePatternFromUrl($namedGroups);
	
		if (preg_match('/^'. $pattern .'$/', $url, $results)) {
			$this->registerFilters($router);
			$this->routeMatch = new RouteMatch($url, $results, $this);
			return $this->routeMatch;
		} else if (preg_match('/^'. $pattern . '/', $url, $results) && !empty($this->collection)) {
			$this->registerFilters($router);

			foreach ($this->collection as $routeCollection) {
				$routeCollection->register($this);
				$match = $routeCollection->match($url, $namedGroups, $router);

				if ($match) {
					$this->routeMatch = new RouteMatch($url, $results, $this);
					$this->setChild($match->getRoute());

					return $match;
				}
			}
		}

		return NULL;	
	}
}