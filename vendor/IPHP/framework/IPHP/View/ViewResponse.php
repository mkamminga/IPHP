<?php
namespace IPHP\View;

class ViewResponse extends Response {
	private $viewPath;

	public function __construct ($viewPath, array $vars = []) {
		parent::__construct($vars);
		$this->viewPath = str_replace('::', DIRECTORY_SEPARATOR, $viewPath);
	}	

	public function getViewPath () {
		return $this->viewPath;
	}

	public function getInjectedVar ($name) {
		return (array_key_exists($name, $this->vars) ? $this->vars[$name] : NULL);
	}
}