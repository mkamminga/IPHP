<?php
namespace App\Controllers;
use IPHP\App\ServiceManager;
use IPHP\Http\Request;
use App\User;
use IPHP\Validation\Validator;
use IPHP\Validation\Rule;
use App\Guards\UserGuard;
use App\Guards\UserSessionGuard;

class AuthController extends Controller {
	protected $userGuard;
	protected $userSessionGuard;
	
	public function __construct(ServiceManager $serviceManager, UserGuard $userGuard, UserSessionGuard $userSessionGuard) {
		parent::__construct($serviceManager);
		$this->userGuard 		= $userGuard;
		$this->userSessionGuard = $userSessionGuard;
	}

	protected function login (Request $request, User $user, Validator $validator) {
		$validator->addRules([
			new Rule('username', 'Gebruikersnaam', ['required', 'alpha_num']),
			new Rule('password', 'Wachtwoord', ['required'])
		]);

		if ($validator->validate($request->all())) {
			$user = $user->findByName($request->get('username'));

			if ($user && password_verify($request->get('password'), $user->retreive('password'))) {
				$password = password_hash($request->get('password'), PASSWORD_BCRYPT, ['cost' => 11]);
				//update salt
				$user->set('password', $password);
				$user->save();

				//set loggedin
				$this->userSessionGuard->setLoggedIn($user->retreive('id'));
				$this->userGuard->setUser($user);

				return true;
			} else {
				$validator->addError('username', $this->sm->getService('translator')->get('validator', 'auth'));
			}
		}

		return false;
	}
}