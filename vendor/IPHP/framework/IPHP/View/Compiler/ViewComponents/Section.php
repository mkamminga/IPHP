<?php
namespace IPHP\View\Compiler\ViewComponents;

use IPHP\View\Compiler\AbstractShowParser;

class Section extends AbstractShowParser {
	protected $name = '';
	protected $content = '';
	protected $uses = [];
	protected $initialized = false;
	protected $hasParent = false;
	protected $parent;

	public function __construct ($name, $content) {
		$this->name = $name;
		$this->content = $content;
		$this->parse();
	}

	public function getName () {
		return $this->name;
	}

	public function setContent ($content) {
		$this->content = $content;
	}

	public function getContent () {
		return $this->content;
	}

	public function getUses () {
		return $this->uses;
	}

	public function usesParent() {
		return $this->hasParent;
	}

	public function setParent(Section $parent) {
		$this->parent = $parent;
	}

	public function addShow (Show $show) {
		$this->shows[] = $show;
	}

	public function getShows ():array {
		return $this->shows;
	}

	public function getOutput() {
		$output =  preg_replace('/[\n\r]*\[parent\][\n\r]*/', ($this->parent ? $this->parent->getOutput() : ''), $this->content);

		return $this->parseShows($output);
	}

	protected function parse () {
		$this->initialized = true;
		$this->content = preg_replace_callback('/>>\s+parent[\n\r]*/', function ($matches) {
			$this->hasParent = true;

			return '[parent]';
		}, $this->content);

		$this->content = preg_replace_callback('/[\n\r]*>>\s+uses\s([a-zA-Z0-9_]+)[\n\r]*/', function ($matches) {
			$this->uses[] = $matches[1];

			return '';
		}, $this->content);


		$this->content = $this->extractShows($this->content);
	}
}