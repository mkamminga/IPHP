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
		if (strlen($this->currentUrl) > 0 && $this->currentUrl[0] != "/"){
			$this->currentUrl = '/' . $this->currentUrl;
		}
	}
	/**
	 * [getMethod description]
	 * @return [type] [description]
	 */
	public function getMethod () {
		return strtolower($_SERVER['REQUEST_METHOD']);
	}
	/**
	 * [currentUrl description]
	 * @return [type] [description]
	 */
	public function currentUrl () {
		return $this->currentUrl;
	}
	/**
	 * [baseUrl description]
	 * @return [type] [description]
	 */
	public function baseUrl () {
		return $this->baseUrl;
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
		if ($this->fromPost($name) != NULL) {
			return $this->fromPost($name);
		} else if ($this->fromGet($name) != NULL) {
			return $this->fromGet($name);
		}

		return NULL;
	}
	/**
	 * [fromPost description]
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function fromPost ($name) {
		return isset($_POST[$name]) ? $_POST[$name] : NULL;
	}
	/**
	 * [fromGet description]
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function fromGet ($name) {
		return isset($_GET[$name]) ? $_GET[$name] : NULL;
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
	/**
	 * [agent description]
	 * @return [type] [description]
	 */
	public function agent () {
		return $_SERVER['HTTP_USER_AGENT'];
	}
	/**
	 * [ip description]
	 * @return [type] [description]
	 */
	public function ip () {
		return $_SERVER['REMOTE_ADDR'];
	}
}