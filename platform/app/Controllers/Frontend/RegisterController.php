<?php
namespace App\Controllers\Frontend;

use IPHP\Validation\Validator;
use IPHP\Validation\Rule;
use IPHP\Http\Request;

use App\Controllers\Controller;
use App\User;
use App\Helpers\InputToAssocHelper;

class RegisterController extends Controller{
    use InputToAssocHelper;
	
	public function showRegister () {
		return $this->view("frontend::auth::register.php", [
			'countries' => $this->getProcessedCountries()
		]);
	}

	public function postRegister (Request $request, Validator $validator) {
		$userModel = new \App\User;

		$validator->addRules([
			new Rule('username', 'Gebruikersnaam', ['required', 'alpha_num', 'min:size=10']),
			new Rule('password', 'Wachtwoord', ['required', 'min:size=5']),
			new Rule('password_confirmation', 'Herhaal wachtwoord', ['required']),
			new Rule('firstname', 'Voornaam', ['required', 'min:size=2', 'max:size=20']),
            new Rule('lastname', 'Achternaam', ['required', 'min:size=2', 'max:size=40']),
            new Rule('country_id', 'Land', ['required']),
            new Rule('city', 'Woonplaats', ['required', 'min:size=2', 'max:size=70']),
            new Rule('address', 'Adres', ['required', 'min:size=4', 'max:size=120']),
            new Rule('zip', 'Postcode', ['required', 'min:size=4', 'max:size=120']),
            new Rule('email', 'Email', ['required', 'email']),
		]);

		$all = $request->all();
		$validator->validate($all);
		//Custom validation
		//Check confirm password
		if ($request->get('password') && $request->get('password_confirmation')
			&& (!$request->get('password')->isEmpty() && $request->get('password_confirmation')->isEmpty()) 
			&& ($request->get('password')->getValue() != $request->get('password_confirmation')->getValue())) {
			$validator->addError('passwords', 'Wachtworden komen niet overeen!');
		}

		if ($request->get('username') && !$request->get('username')->isEmpty()) {
			if (!$userModel->isUnique($request->get('username')->getValue())) {
				$validator->addError('username', 'Gebruikersnaam is bezet!');
			}
		}

		if (!$validator->hasErrors()){
			$group = (new \App\UserGroup)->getByname('user');
			if ($group){
				$userModel->set('username', $all['username']->getValue());
				$userModel->set('password', password_hash($all['password']->getValue(), PASSWORD_BCRYPT, ['cost' => 10]));
				$userModel->set('firstname', $all['firstname']->getValue());
				$userModel->set('lastname', $all['lastname']->getValue());
				$userModel->set('country_id', $all['country_id']->getValue());
				$userModel->set('city', $all['city']->getValue());
				$userModel->set('address', $all['address']->getValue());
				$userModel->set('zip', $all['zip']->getValue());
				$userModel->set('email', $all['email']->getValue());
				$userModel->set('active', 1);
				$userModel->set('group_id', $group->retreive('id'));

				if ($userModel->save()) {
					return $this->view('frontend::auth::registerd.php');
				} 
			}
			//something has gone wrong
			$validator->addError('main', 'Er is een onbekende fout opgetreden tijdens het opslaan!');
		} 
		
		return $this->showRegister()->setVar('errors', $validator->getErrors());
	}
	/**
     * [getProcessedVat description]
     * @return [type] [description]
     */
    private function getProcessedCountries() {
        $country = new \App\Country;
        return $this->collectionToAssoc($country->getCollection($country->select()->orderBy('name')), 'id', 'name');
    }
}