<?php
namespace App\Controllers;
use IPHP\App\ServiceManager;

class Controller {
	protected $sm;
	
	public function __construct (ServiceManager $serviceManager) {
		$this->sm = $serviceManager;
	}

	public function redirect() {
		return $this->sm->getService('redirect');
	}
}