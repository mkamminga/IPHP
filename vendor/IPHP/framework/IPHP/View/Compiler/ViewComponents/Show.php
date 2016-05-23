<?php
namespace IPHP\View\Compiler\ViewComponents;

class Show {
	private $name = '';
	private $defaultContent = '';
	private $content = '';
	private $section = null;

	public function __construct ($name, $defaultContent) {
		$this->name = $name;
		$this->defaultContent = $defaultContent;
	}

	public function getName (): string {
		return $this->name;
	}

	public function getOutput (): string {
		if ($this->section) {
			return $this->section->getOutput();
		} else {
			return $this->defaultContent;
		}
	}

	public function setSection(Section $section) {
		$this->section = $section;
	}

	public function getSection():Section {
		return $this->section;
	}
}