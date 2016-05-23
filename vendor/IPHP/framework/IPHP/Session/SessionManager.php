<?php
namespace IPHP\Session;

class SessionManager {
	private $started = false;
	public function __construct (string $savePath = '') {
		if (!empty($savePath)) {
			session_save_path($savePath);
		}
	}

	public function start (array $options = []) {
		if (!$this->started){
			session_start($options);
		}
	}

	public function set ($key, $value) {
		$_SESSION[$key] = $value;
	}

	public function get ($key) {
		return isset($_SESSION[$key]) ? $_SESSION[$key] : NULL; 
	}

	public function reset () {
		session_unset();
	}

	public function reinit () {
		session_reset();
	}

	public function abort () {
		session_abort();
	}

	public function save () {
		session_write_close();
	}
}