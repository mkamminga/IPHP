<?php
namespace App\Filters;

use IPHP\App\ServiceManager;
use IPHP\Events\EventManager;
use IPHP\Http\Routing\IRouteFilterable;

class RegisterMainViewComposerFilter implements IRouteFilterable {
	private $eventManager;

	public function __construct (EventManager $eventManager) {
		$this->eventManager = $eventManager;
	}

	public function handle () {
		$this->eventManager->register('route.dispatch', function (ServiceManager $sm) {
			$vc = new \App\Composers\MainViewComposer($sm);
			$vc->compose();
		});
	} 
}