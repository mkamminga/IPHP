<?php
namespace IPHP\Http;

class Request {
	protected $currentUrl = NULL;
	protected $baseUrl = NULL;
	protected $all = [];


	public function __construct () {
		//setup the vars correctly
		$this->baseUrl = dirname($_SERVER['SCRIPT_NAME']);
		$this->currentUrl = str_replace($this->baseUrl, '', urldecode($_SERVER['REQUEST_URI']));
	}
	/**
	 * [getMethod description]
	 * @return [type] [description]
	 */
	public function getMethod () {
		return $_SERVER['REQUEST_METHOD'];
	}
	/**
	 * [currentUrl description]
	 * @return [type] [description]
	 */
	public function currentUrl () {
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
	 * [get description]
	 * @return [type] [description]
	 */
	public function get ($name) {
		//
	}
	/**
	 * returns merged post, get, files
	 * @return [type] [description]
	 */
	public function all () {
		if (empty($this->all)) {
			$this->all = $_POST;
			$this->all+= $_GET;
		}

		return $this->all;
	}
}