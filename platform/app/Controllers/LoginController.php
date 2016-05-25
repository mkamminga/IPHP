<?php
namespace App\Controllers;
use IPHP\App\ServiceManager;
use IPHP\Http\Request;
use IPHP\View\ViewResponse;
use App\Guards\UserGuard;
use App\Guards\UserSessionGuard;
use App\User;

class LoginController extends controller {
	private $userGuard;
	private $userSessionGuard;
	
	public function __construct(ServiceManager $serviceManager, UserGuard $userGuard, UserSessionGuard $userSessionGuard) {
		parent::__construct($serviceManager);
		$this->userGuard 		= $userGuard;
		$this->userSessionGuard = $userSessionGuard;
	}

	public function showLogin () {
		return new ViewResponse("login.php");
	}

	public function postLogin (Request $request, User $user) {
		
		if ($this->userGuard->loggedIn()){
			$user = $user->find(1);

			if (password_verify('test', $user->retreive('password'))) {
				$testPass = password_hash("test", PASSWORD_BCRYPT, ['cost' => 12]);
				//update salt
				$user->set('password', $testPass);
				$user->save();
				//set loggedin
				$this->userSessionGuard->setLoggedIn($user->retreive('id'));
				$this->userGuard->setUser($user);

				$this->redirect()->toRoute('LoginGet');
			} else {
				exit;
			}
		} else {
			return $this->showLogin();
		}


	}
}