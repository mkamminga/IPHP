<?php
namespace IPHP\View;

use IPHP\Http\Response;

class ViewResponse {
	private $viewPath;
	private $vars = [];
	private $httpResponse;

	public function __construct ($viewPath, array $vars = []) {
		$this->viewPath = $viewPath;
		$this->vars = $vars;

		$this->httpResponse = new Response;
	}	

	public function getViewPath () {
		return $this->viewPath;
	}

	public function getInjectedVars () {
		return $this->vars;
	}

	public function setVar ($key, $value) {
		$this->vars[$key] = $value;

		return $this;
	}

	public function getInjectedVar ($name) {
		return (array_key_exists($name, $this->vars) ? $this->vars[$name] : NULL);
	}

	public function getHttpResponse () {
		return $this->httpResponse;
	}
}