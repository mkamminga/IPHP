<?php
namespace App\Guards;
use App\User;

class UserGuard {
	private $user;

	public function setUser (User $user) {
		$this->user = $user;
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
		//
	}

	public function isAdmin () {
		return $this->loggedIn() && $this->user->hasRole('admin');
	}

	public function isGuest () {
		return $this->loggedIn();
	}
}