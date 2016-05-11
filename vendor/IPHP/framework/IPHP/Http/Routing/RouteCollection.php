<?php
namespace IPHP\Http\Routing;

class RouteCollection extends AbstractRoute {
	protected $subRoutes;

	public function __construct ($suffex = '', array $filters = [], array $subRoutes = []) {
		parent::__construct($suffex, $filters);
		$this->subRoutes = $subRoutes;
	}

	public function match ($url, array $namedGroups = [], Router $router) {
		$pattern = $this->routePatternFromUrl($namedGroups);

		if (preg_match('/^'. $pattern .'/', $url)) {
			$this->registerFilters($router);

			foreach ($this->subRoutes as $route) {
				$route->register($this);
				$match = $route->match($url, $namedGroups, $router);
				if ($match) {
					return $match;
				}
			}
			
		}

		return NULL;	
	}
}