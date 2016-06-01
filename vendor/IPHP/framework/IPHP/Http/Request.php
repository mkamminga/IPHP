<?php
namespace IPHP\Http;

class Request {
	protected $currentUrl = NULL;
	protected $baseUrl = NULL;
	protected $all = [];
	protected $post = [];
	protected $get = [];
	protected $files = [];


	public function __construct () {
		$this->initUrls();
		$this->initInputs();
	}

	private function initUrls () {
		//setup the vars correctly
		$this->baseUrl 			= dirname($_SERVER['SCRIPT_NAME']);
		$this->currentUrl 		= urldecode($_SERVER['REQUEST_URI']);
		if (strlen($this->baseUrl) > 1){
			$this->currentUrl = str_replace($this->baseUrl, '', $this->currentUrl);
		}
		
		if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
			$this->currentUrl = str_replace('?' . $_SERVER['QUERY_STRING'], '', $this->currentUrl);
		}

		$currentUrlLength = strlen($this->currentUrl);
		if ($currentUrlLength > 1 && $this->currentUrl[0] != "/"){
			$this->currentUrl = '/' . $this->currentUrl;
		} else if ($currentUrlLength == 1 &&  $this->currentUrl[0] == '/') {
			$this->currentUrl = '';
		}
	}

	private function initInputs () {
		

		$createInputs = function ($data, $class, $from) {
			$return = [];
			foreach ($data as $key => $value) {
				$return[$key] = new $class($key, $value, $from);
			}

			return $return;
		};

		$this->post 	= $createInputs($_POST, RequestInput::class, 'post');
		$this->get 		= $createInputs($_GET, RequestInput::class, 'get');
		$this->files 	= $createInputs($_FILES, RequestUploadedFile::class, 'file');
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
		} else if ($this->fromFiles($name) != NULL) {
			return $this->fromFiles($name);
		}

		return NULL;
	}
	/**
	 * [fromPost description]
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function fromPost ($name) {
		return isset($this->post[$name]) ? $this->post[$name] : NULL;
	}
	/**
	 * [fromGet description]
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function fromGet ($name) {
		return isset($this->get[$name]) ? $this->get[$name] : NULL;
	}
	/**
	 * [fromFiles description]
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public function fromFiles ($name) {
		return isset($this->files[$name]) ? $this->files[$name] : NULL;
	}
	/**
	 * returns merged post, get, files
	 * @return [type] [description]
	 */
	public function all () {
		if (empty($this->all)) {
			$this->all+= $this->get;
			$this->all+= $this->post;
			$this->all+= $this->files;
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