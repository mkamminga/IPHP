<?php
namespace App\Filters;

use IPHP\Http\Routing\IRouteFilterable;
use IPHP\App\ServiceManager;
use App\Guards\UserGuard;

class UserLoggedinFilter implements IRouteFilterable {
	private $serviceManager;
	private $user;
	private $group;
	private $params = [];

	public function __construct (ServiceManager $sm, UserGuard $user, string $group, string $route, array $params = []) {
		$this->serviceManager 	= $sm;
		$this->user 			= $user;
		$this->group 			= $group;
		$this->route 			= $route;
		$this->params 			= $params;
	}

	public function handle () {
		if (!$this->user->isAdmin()) {
			$this->redirect();
		}
	}

	private function redirect () {
		$this->serviceManager->getService('redirect')->toRoute($this->route, $this->params);
	}
}