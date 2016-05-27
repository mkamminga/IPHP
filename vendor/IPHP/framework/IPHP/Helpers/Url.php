<?php
namespace IPHP\Helpers;

use IPHP\Http\Routing\Router;

class Url {
	private $router;
	public function __construct (Router $router) {
		$this->router = $router;
	}

	public function route (string $name, array $params = []) :string {
		return $this->router->urlByRouteName($name, $params);
	}
}