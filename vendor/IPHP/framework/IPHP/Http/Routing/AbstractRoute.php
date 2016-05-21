<?php
namespace IPHP\Http\Routing;

abstract class AbstractRoute {
	protected $url = '';
	protected $filters = [];

	public function __construct ($url, array $filters = []) {
		$this->url = preg_replace('/\[([a-z0-9_]+)(\:)([a-z0-9_]+)\]/', '(?<$3>[$1])', $url);
		$this->filters = $filters;
	}

	public function getUrl(): string {
		return $this->url;
	}

	public function getFilters(): array {
		return $this->filters;
	}

	abstract public function match($url, array $groups = [], Router $router);
	
	public function register(AbstractRoute $registerable){
		$parentRoute = $registerable->getUrl();
		$checkAndChangeTrailingSlash = function ($url){
			if (strlen($url) > 0 && $url[0] != '/') {
				$url = '/' . $url;
			}
			return $url;
		};

		$this->url = $checkAndChangeTrailingSlash($this->url);

		if (strlen($this->url) > 0 && $this->url[0] != '/') {
			$this->url = '/' . $this->url;
		}
		$this->url = $parentRoute . $this->url;
		$this->url = str_replace('//', '/', $this->url);
		$this->url = $checkAndChangeTrailingSlash($this->url);
	}

	protected function routePatternFromUrl (array $namedGroups = []) {
		$pattern = str_replace(array_keys($namedGroups), array_values($namedGroups), $this->url);
		
		if (strlen($pattern) > 0 && $pattern[0] != '/') {
			$pattern = '/' . $pattern;
		}

		if (strlen($pattern) > 1 && $pattern[strlen($pattern) - 1] == '/') {
			$pattern = substr($pattern, 0, strlen($pattern) - 1) . '[/]?';
		}
		
		return str_replace('/', '\\/', $pattern);
	}

	public function registerFilters (Router $router) {
		if (!empty($this->filters)){
			foreach ($this->filters as $filter => $params) {
				$router->registerFilter($filter, $params);
			}
		}
	}

	abstract public function findMatchByName (string $name, Router $router);
}