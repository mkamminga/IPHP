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

	public function match ($url, array $namedGroups = [], Router $router, $method = 'get') {
		$results = [];
		
		$pattern = $this->routePatternFromUrl($namedGroups);
	
		if (($method == 'all' || $this->callableByMethod == $method) 
			&& preg_match('/^'. $pattern .'$/', $url, $results)) {
			$this->registerFilters($router);
			$this->registerRouteMatch($url, $results, $router);

			return $this->routeMatch;
		} else if (preg_match('/^'. $pattern . '/', $url, $results) && !empty($this->collection)) {
			$this->registerFilters($router);

			foreach ($this->collection as $routeCollection) {
				$routeCollection->register($this);
				$match = $routeCollection->match($url, $namedGroups, $router, $method);

				if ($match) {
					$this->registerRouteMatch($url, $results, $router);
					$this->setChild($match->getRoute());

					return $match;
				}
			}
		}

		return NULL;	
	}

	public function findMatchByName (string $name, Router $router) {
		$router->registerRoute($this);
		if ($name == $this->name) {
			return $this;
		}
		

		if (!empty($this->collection)) {
			foreach ($this->collection as $routeCollection) {
				$routeCollection->register($this);
				$match = $routeCollection->findMatchByName($name, $router);

				if ($match) {
					return $match;
				}
			}
		}

		return NULL;
	}

	public function getUrlFromParams (array $params = [], array $namedGroups = []) {
		$url = $this->url;

		if (strlen($url) > 0 && $url[strlen($url) - 1] == '?') {
			$url = substr($url, 0, -1);
		}

		if (!empty($params)) {
			$url = preg_replace('/\(\?\<(.*?)\>\[(.*?)\]\)/', '[$1]', $url);

			foreach ($params as $key => $value) {
				$url = str_replace('['. $key .']', $value, $url);
			}
		}

		$pattern = $this->routePatternFromUrl($namedGroups);
		
		if (preg_match('/^'. $pattern .'$/', $url)) {
			return $url;
		} else {
			throw new \Exception("Params do not match!");
		}
	} 	

	private function registerRouteMatch (string $url, array $results = [], Router $router) {
		$this->routeMatch = new RouteMatch($url, $results, $this);
		$router->registerRoute($this);
	}
}