<?php
namespace App\Controllers;
use IPHP\App\ServiceManager;
use IPHP\View\ViewResponse;

class Controller {
	protected $sm;
	
	public function __construct (ServiceManager $serviceManager) {
		$this->sm = $serviceManager;
	}

	public function redirect() {
		return $this->sm->getService('redirect');
	}

	public function view (string $name, array $vars = []) {
		return new ViewResponse($name, $vars);
	}
}