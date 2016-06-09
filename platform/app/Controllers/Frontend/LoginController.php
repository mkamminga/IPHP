<?php
namespace App\Controllers\Frontend;
use App\Controllers\AuthController;
use IPHP\Validation\Validator;
use IPHP\App\ServiceManager;
use IPHP\Http\Request;
use App\User;

class LoginController extends AuthController {

	public function showLogin () {
		if ($this->userGuard->loggedIn()){
			$this->redirect()->to('/');
		}

		return $this->view("frontend::auth::login.php");
	}

	public function postLogin (Request $request, User $user, Validator $validator) {
		if ($this->login($request, $user, $validator)){
			$this->redirect()->toRoute('Home');
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