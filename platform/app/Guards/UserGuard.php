<?php
namespace App\Guards;

class UserGuard {
	public function loggedIn () {
		return false;
	}
}