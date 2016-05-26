<?php
namespace IPHP\Http\Routing;

class RouteCollection extends AbstractRoute {
	protected $subRoutes;

	public function __construct ($suffex = '', array $filters = [], array $subRoutes = []) {
		parent::__construct($suffex, $filters);
		$this->subRoutes = $subRoutes;
	}

	public function match ($url, array $namedGroups = [], Router $router, $method = 'get') {
		$pattern = $this->routePatternFromUrl($namedGroups);

		if (preg_match('/^'. $pattern .'/', $url)) {
			foreach ($this->subRoutes as $route) {
				$route->register($this);
				$match = $route->match($url, $namedGroups, $router, $method);
				if ($match) {
					$this->registerFilters($router);
					return $match;
				}
			}
			
		}

		return NULL;	
	}

	public function findMatchByName (string $name, Router $router) {
		if (!empty($this->subRoutes)) {
			foreach ($this->subRoutes as $route) {
				$route->register($this);
				$match = $route->findMatchByName($name, $router);
				if ($match) {
					return $match;
				}
			}
		}

		return NULL;
	}
}