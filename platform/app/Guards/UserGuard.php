<?php
namespace App\Guards;
use App\User;

class UserGuard {
	private $user;

	public function setUser (User $user) {
		$this->user = $user;
	}

	public function getUser () {
		return $this->user;
	}

	public function getUsername () {
		if ($this->user) {
			return $this->user->retreive('username');
		}

		return '';
	}

	public function loggedIn () {
		return $this->user != NULL;
	}

	public function hasRole ($name = '') {
		$group = $this->user->getRelated('role');

		return strtolower($name) == strtolower($group->retreive('name'));
	}

	public function isAdmin () {
		return $this->loggedIn() 
			&& ($this->hasRole('admin') || $this->hasRole('cmsadmin') || $this->hasRole('superadmin'));
	}

	public function isGuest () {
		return $this->loggedIn();
	}
}