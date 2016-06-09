<?php
namespace Breadcrumbs;

use Closure;

class Breadcrumb {
	private $routeName = '';
	private $title = '';
	private $url = '';
	private $childs = [];
	private $aliases = [];
	private $parent = NULL;
	private $requiredParams = [];

	public function __construct ($routeName, array $requiredParams = [], Closure $resolver) {
		$this->routeName 		= $routeName;
		$this->requiredParams 	= $requiredParams;
		$this->resolver 		= $resolver;
	}

	public static function make($routeName, array $requiredParams = [], Closure $resolver) {
		return new self($routeName, $requiredParams,$resolver);
	}

	public function addChild (Breadcrumb $child):Breadcrumb {
		$this->childs[] = $child;

		$child->setParent($this);

		return $this;
	}

	public function getChilds ():array {
		return $this->childs;
	}

	public function addAlias (string $routeName) {
		$this->aliases[] = $routeName;

		return $this;
	}

	public function setParent (Breadcrumb $parent) {
		$this->parent = $parent;
	}

	public function getParent () {
		return $this->parent;
	}

	public function setTitle ($title = '') {
		$this->title = $title;
	}

	public function getTitle () {
		return $this->title;
	}

	public function getName () {
		return $this->routeName;
	}

	public function match (string $routeName): bool {
		if ($this->routeName == $routeName || (!empty($this->aliases) && in_array($routeName, $this->aliases))) {
			return true;
		}

		return false;
	}

	public function getRequiredParams (): array {
		return $this->requiredParams;
	}

	public function setUrl (string $url) {
		return $this->url = $url;
	}

	public function getUrl () {
		return $this->url;
	}

	public function resolve (array $params = []) {
		$resolver = $this->resolver;
		$resolver($this, $params);
	}
}