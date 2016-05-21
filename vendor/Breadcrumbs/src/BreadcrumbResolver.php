<?php
namespace Breadcrumbs;

use IPHP\Http\Routing\Router;
use IPHP\Http\Routing\RouteMatch;

class BreadcrumbResolver {
	private $router;
	private $breadcrumbs;
	private $compiled = [];

	public function __construct (Router $router, array $breadcrumbs = []) {
		$this->router = $router;
		$this->breadcrumbs = $breadcrumbs;
	}

	public function findByName ($routeName) {
		foreach ($this->breadcrumbs as $breadcrumb) {
			if ($breadcrumb->getName() == $routeName) {
				return $breadcrumb;
			} else if ($breadcrumb->getChild()) {
				$current = $breadcrumb->getChild();
				while ($current) {
					if ($current->getName() == $routeName) {
						return $current;
					}
					$current = $current->getChild();
				}
			}
		}

		return NULL;
	}

	public function compile (RouteMatch $routeMatch): bool {
		$this->compiled = [];
		$route = $routeMatch->getRoute();

		if ($route) {
			$breadcrumb = $this->findByName($route->getName());

			if ($breadcrumb) {
				while ($breadcrumb != NULL) {
					$params = [];
					$requiredParams = $breadcrumb->getRequiredParams();

					foreach ($requiredParams as $requiredKey) {
						if ($routeMatch->hasParam($requiredKey)) {
							$params[$requiredKey] = $routeMatch->getParam($requiredKey);
						} else {
							throw new \Exception("Missing required param key (". $requiredKey .") for breadcrumb route '". $breadcrumb->getName() ."'");
						}
					}

					$breadcrumb->setUrl($this->router->urlByRouteName($breadcrumb->getName(), $params));
					$breadcrumb->resolve($params);

					array_unshift($this->compiled, $breadcrumb);

					$breadcrumb = $breadcrumb->getParent();
				}

				return true;
			}
		}

		return false;
	}

	public function getCompiled () {
		return $this->compiled;
	}
}