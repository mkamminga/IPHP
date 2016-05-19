<?php
namespace Breadcrumbs;

class Breadcrumb {
	private $routeName;
	private $title;
	private $url;
	private $child;
	private $parent;

	public function setChild (Breadcrumb $child) {
		$this->child = $child;
	}

	public function setParent (Breadcrumb $parent) {
		$this->parent = $parent;
	}

	public function setCollection (\Closure $collection) {
		$this->child = $collection();
	}

	public function setTitle ($title = '') {
		$this->title = $title;
	}

	public function getTitle () {
		return $this->title;
	}

	public function getName ($routeName) {
		$this->routeName = $routeName;
	}
}