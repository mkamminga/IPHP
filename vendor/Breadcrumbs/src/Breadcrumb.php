<?php
namespace Breadcrumbs;

use Closure;

class Breadcrumb {
	private $routeName = '';
	private $title = '';
	private $url = '';
	private $child = NULL;
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

	public function setChild (Breadcrumb $child) {
		$this->child = $child;

		$child->setParent($this);

		return $this;
	}

	public function getChild () {
		return $this->child;
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