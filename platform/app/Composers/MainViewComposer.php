<?php
namespace App\Composers;

use IPHP\App\ServiceManager;
use IPHP\View\ViewResponse;
use Breadcrumbs\BreadcrumbResolver;
use App\Navigation;

class MainViewComposer {
	protected $sm;

	public function __construct (ServiceManager $sm) {
		$this->sm = $sm;
	}

	public function resolveUser () {
		if ($this->sm->hasService('viewResponse') 
				&& $this->sm->getService('viewResponse')->compileable() 
				&& $this->sm->hasService('userGuard')) {
			$this->sm->getService('viewResponse')->setVar('userGuard', $this->sm->getService('userGuard'));
		}
	}

	public function compose () {
		$this->resolveUser();
	}
}