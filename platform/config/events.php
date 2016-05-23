<?php
use IPHP\App\ServiceManager;
use Breadcrumbs\BreadcrumbResolver;
/**
 * Providers
 */
return [
	'kernel.booted' => [[
		'stop' => false,
		'listener' => function (ServiceManager $sm) {
			$sessionManager 	= new \IPHP\Session\SessionManager;
			$sessionManager->start();

			$userSessionGuard 	= new \App\Guards\UserSessionGuard($sessionManager, 'user');
			$user 				= new \App\User;
			$userGuard 			= new \App\Guards\UserGuard;

			$request 	= $sm->getService('request');
			$key 		= $request->ip() . '-'. $request->agent();
			
			if ($userSessionGuard->loggedIn()) {
				if ($userSessionGuard->same($key)) {
					$user 		= $user->find((int)$userSessionGuard->getId());
					$userGuard->setUser($user);
				} else {
					$userSessionGuard->logout();
					//security issue
					var_dump("Not same");
					exit;
				}
			} else {
				$userSessionGuard->setKey($key);
			}

			$sm->registerInstanceAlias('userGuard', \App\Guards\UserGuard::class, $userGuard);
			$sm->registerInstanceAlias('userSessionGuard', \App\Guards\UserSessionGuard::class, $userSessionGuard);
			$sm->registerInstanceAlias('sessionManager', \IPHP\Session\SessionManager::class, $sessionManager);
		}
	]],
	'route.dispatch' => [[
		'stop' => false,
		'listener' => function (ServiceManager $sm) {

			if ($sm->hasService('viewResponse')) {
				$viewResponse = $sm->getService('viewResponse');
				$router = $sm->getService('router');

				$breadCrumbs 	= include 'breadcrumbs.php';
				$prefix 		= $sm->getService('request')->baseUrl();
				$resolver 		= new BreadcrumbResolver($router, $breadCrumbs, $prefix);

				$resolver->compile($router->getRouteMatch());
				$viewResponse->setVar('breadcrumbs', $resolver->getCompiled());
			}
		}
	]]
];