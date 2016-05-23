<?php
namespace IPHP\Validation;

class Rule {
	private $inputName;
	private $fieldName;
	private $requirements 	= [];
	private $customMessages = [];

	public function __construct (string $inputName, string $fieldName, array $requirements, array $customMessages = []) {
		$this->inputName 		= $inputName;
		$this->fieldName 		= $fieldName;
		$this->requirements		= $requirements;
		$this->customMessages	= $customMessages;
	}

	public function getInputname () {
		return $this->inputName;
	}

	public function getFieldname () {
		return $this->fieldName;
	}

	public function getRequirements () {
		return $this->requirements;
	}

	public function hasMessageFor ($name) {
		return isset($this->customMessages[$name]);
	}

	public function getMessageFor ($name) {
		if ($this->hasMessageFor($name)) {
			return $this->customMessages[$name];
		}
	}
}