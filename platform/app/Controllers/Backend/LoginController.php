<?php
namespace App\Controllers\Backend;

use IPHP\Validation\Validator;
use IPHP\App\ServiceManager;
use IPHP\Http\Request;
use IPHP\View\ViewResponse;

use App\Controllers\AuthController;
use App\User;

class LoginController extends AuthController {

	public function showLogin () {
		if ($this->userGuard->loggedIn()){
			$this->redirect()->toRoute('Dashboard');
		}

		return new ViewResponse("cms::login.php");
	}

	public function postLogin (Request $request, User $user, Validator $validator) {
		if ($this->login($request, $user, $validator)){
			$this->redirect()->toRoute('Dashboard');
		} else {
			return $this->showLogin()->setVar('errors', $validator->getErrors());
		}
	}

	public function logout () {
		//set loggedin
		$this->userSessionGuard->logout();
		$this->redirect()->toRoute('Dashboard');
	}
}