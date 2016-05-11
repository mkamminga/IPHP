<?php
namespace IPHP\Http;

class Request {
	protected $currentUrl = NULL;
	protected $all;


	public function __construct () {
		//setup the vars correctly
	}

	public function getMethod () {
		return $_SERVER['REQUEST_METHOD'];
	}

	public function currentUrl () {
		if (!$this->currentUrl) {
			$this->currentUrl = str_replace(dirname($_SERVER['SCRIPT_NAME']), '', urldecode($_SERVER['REQUEST_URI']));
		}
		return $this->currentUrl;
	}
	/**
	 * The full url of the request
	 * 
	 * @return [type] [description]
	 */
	public function fullUrl () {
		return $_SERVER['REQUEST_URI'];
	}
	/**
	 * returns merged post, get, files
	 * @return [type] [description]
	 */
	public function all () {
		//
	}
}