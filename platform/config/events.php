<?php
use IPHP\App\ServiceManager;
use Breadcrumbs\Breadcrumb;
use Breadcrumbs\BreadcrumbResolver;
/**
 * Providers
 */
return [
	'route.dispatch' => [[
		'stop' => false,
		'listener' => function (ServiceManager $sm) {

			if ($sm->hasService('viewResponse')) {
				$viewResponse = $sm->getService('viewResponse');
				$router = $sm->getService('router');

				$breadCrumbs = [
					Breadcrumb::make('HomeB', ['id', 'title'], function (Breadcrumb $breadcrumb, array $params = []) {
						$breadcrumb->setTitle('Title');
					})->setChild(
						Breadcrumb::make('HomeC', ['id', 'title'], function (Breadcrumb $breadcrumb, array $params = []) {
							$breadcrumb->setTitle('Id : '. $params['id']);
						})
					)
				];

				$resolver = new BreadcrumbResolver($router, $breadCrumbs);
				$resolver->compile($router->getRouteMatch());
				$viewResponse->setVar('breadcrumbs', $resolver->getCompiled());
			}
		}
	]]
];