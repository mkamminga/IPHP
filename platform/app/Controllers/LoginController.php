<?php
namespace App\Controllers;
use IPHP\App\ServiceManager;
use IPHP\Http\Request;
use App\Guards\UserGuard;
use App\Guards\UserSessionGuard;
use App\User;

class LoginController {
	private $sm;
	private $userGuard;
	private $userSessionGuard;
	
	public function __construct(ServiceManager $serviceManager,UserGuard $userGuard, UserSessionGuard $userSessionGuard) {
		$this->sm 				= $serviceManager;
		$this->userGuard 		= $userGuard;
		$this->userSessionGuard = $userSessionGuard;
	}

	public function showLogin (User $user) {
		if (!$this->userGuard->loggedIn()){
			$user = $user->find(1);

			if (password_verify('test', $user->retreive('password'))) {
				$testPass = password_hash("test", PASSWORD_BCRYPT, ['cost' => 12]);

				$user->set('password', $testPass);

				var_dump($user->save());

				$this->userSessionGuard->setLoggedIn($user->retreive('id'));
				$this->userGuard->setUser($user);
			} else {
				exit;
			}
		}
	}

	public function loginPost (Request $request, User $user) {}
}