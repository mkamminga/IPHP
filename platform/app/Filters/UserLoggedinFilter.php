<?php
namespace App\Filters;

use IPHP\Http\Routing\IRouteFilterable;
use IPHP\App\ServiceManager;
use App\Guards\UserGuard;

class UserLoggedinFilter implements IRouteFilterable {
	private $serviceManager;
	private $user;

	public function __construct (ServiceManager $sm, UserGuard $user, $group) {
		$this->serviceManager = $sm;
		$this->user = $user;
	}

	public function handle () {
		return $this->user->loggedIn();
	} 
}