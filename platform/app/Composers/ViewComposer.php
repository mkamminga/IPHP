<?php
namespace App\Composers;

use IPHP\App\ServiceManager;
use Breadcrumbs\BreadcrumbResolver;
use App\Navigation;

class ViewComposer {
	private $sm;

	public function __construct (ServiceManager $sm) {
		$this->sm = $sm;
	}

	public function resolveBreadCrumbs () {
		if ($this->sm->hasService('viewResponse')) {
			$viewResponse = $this->sm->getService('viewResponse');
			$router = $this->sm->getService('router');

			$breadCrumbs 	= include config_path . DIRECTORY_SEPARATOR .  'breadcrumbs.php';
			$resolver 		= new BreadcrumbResolver($router, $breadCrumbs);

			$resolver->compile($router->getRouteMatch());
			$viewResponse->setVar('breadcrumbs', $resolver->getCompiled());
		}
	}

	public function resolveMenu () {
		if ($this->sm->hasService('viewResponse')) {
			$viewResponse 	= $this->sm->getService('viewResponse');
			$navigation 	= new Navigation;
			$menu 			= $navigation->get($navigation->allSorted());

			$viewResponse->setVar('menus', $menu);
		}
	}

	public function compose () {
		$this->resolveBreadCrumbs();
		$this->resolveMenu();
	}
}