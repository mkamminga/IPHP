<?php
namespace IPHP\Http\Routing;

class RouteMatch {
	protected $route;
	protected $url;
	protected $params = [];

	public function __construct ($url, array $params, Route $route) {
		$this->route = $route;
		$this->url = $url;	
		$this->params = $params;
	}

	public function getRoute () {
		return $this->route;
	}

	public function getMatchedUrl () {
		return $this->url;		
	}

	public function getParams () {
		return $this->params;
	}

	public function hasParam ($name) {
		return array_key_exists($name, $this->params);
	}

	public function getParam ($name) {
		if ($this->hasParam($name)) {
			return $this->params[$name];
		}

		return NULL;
	}
}