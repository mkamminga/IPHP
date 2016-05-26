<?php
use IPHP\App\ServiceManager;
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
					$user 		= $user->with('role')->find((int)$userSessionGuard->getId());
					$userGuard->setUser($user);
				} else {
					$userSessionGuard->logout();
					//security issue
					exit;
				}
			} else {
				$userSessionGuard->setKey($key);
			}

			$sm->registerInstanceAlias('userGuard', \App\Guards\UserGuard::class, $userGuard);
			$sm->registerInstanceAlias('userSessionGuard', \App\Guards\UserSessionGuard::class, $userSessionGuard);
			$sm->registerInstanceAlias('sessionManager', \IPHP\Session\SessionManager::class, $sessionManager);
		}
	]]
];