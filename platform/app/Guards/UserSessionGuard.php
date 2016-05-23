<?php
namespace App\Guards;

use IPHP\Session\SessionManager;

class UserSessionGuard {
	private $key = '';
	private $loggedIn = false;
	private $id = 0; 
	private $sessionManager;
	private $sessionKey;
	private $initialized = false;

	public function __construct (SessionManager $sessionManager, string $sessionKey = '') {
		$this->sessionManager 	= $sessionManager;
		$this->sessionKey 		= $sessionKey;

		$sessionUser 			= $sessionManager->get($sessionKey);

		if ($sessionUser != NULL){
			$this->initialized = true;
			$this->loggedIn 	= isset($sessionUser['loggedIn']) ? (bool)$sessionUser['loggedIn'] : false;
			$this->key 			= isset($sessionUser['key']) ? (string)$sessionUser['key'] : false;
			$this->id 			= isset($sessionUser['id']) ? (int)$sessionUser['id'] : 0;
		}
	}

	public function initialized () {
		return $this->initialized;
	}

	public function same ($key) {
		return $this->key == $key;
	}

	public function getId () {
		return $this->id;
	}

	public function loggedIn () {
		return $this->loggedIn;
	}

	public function setKey (string $key) {
		$this->key = $key;
	}

	public function setLoggedIn (int $id) {
		$this->id = $id;

		$this->sessionManager->set($this->sessionKey, [
			'loggedIn' 	=> true,
			'id' 		=> $id,
			'key'		=> $this->key
		]);
	}

	public function logout () {
		$this->sessionManager->set($this->sessionKey, NULL);

		$this->id = 0;
		$this->key = NULL;
		$this->loggedIn = false;
	}
}