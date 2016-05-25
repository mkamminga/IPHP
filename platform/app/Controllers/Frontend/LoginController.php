<?php
namespace App\Controllers\Frontend;
use App\Controllers\Controller;
use IPHP\App\ServiceManager;
use IPHP\Http\Request;
use IPHP\View\ViewResponse;
use App\Guards\UserGuard;
use App\Guards\UserSessionGuard;
use App\User;
use IPHP\Validation\Validator;
use IPHP\Validation\Rule;

class LoginController extends Controller {
	private $userGuard;
	private $userSessionGuard;
	
	public function __construct(ServiceManager $serviceManager, UserGuard $userGuard, UserSessionGuard $userSessionGuard) {
		parent::__construct($serviceManager);
		$this->userGuard 		= $userGuard;
		$this->userSessionGuard = $userSessionGuard;
	}

	public function showLogin () {
		if ($this->userGuard->loggedIn()){
			$this->redirect()->to('/');
		}

		return new ViewResponse("login.php");
	}

	public function postLogin (Request $request, User $user, Validator $validator) {
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

				$this->redirect()->toRoute('Home');
			} else {
				return $this->showLogin()->setVar('errors', [
					'username' => $this->sm->getService('translator')->get('validator', 'auth')
				]);
			}
		} else {
			return $this->showLogin()->setVar('errors', $validator->getErrors());
		}
	}

	public function logout () {
		//set loggedin
		$this->userSessionGuard->logout();
		$this->redirect()->toRoute('Home');
	}
}