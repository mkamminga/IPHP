<?php
namespace IPHP\Http;

class RequestInput {
	protected $value;
	protected $name;
	protected $from;

	public function __construct ($name, $mixedValue, $from) {
		$this->value = $mixedValue;
		$this->name = $name;
		$this->from = $from;
	}

	public function getName () {
		return $this->name;
	}

	public function getFrom () {
		return $this->from;
	}

	public function getValue() {
		return $this->value;
	}

	public function is ($type) {
		return $this->type() == $type;
	}

	public function type () {
		return getType($this->value);
	}
	
	public function isEmpty () {
		return empty($this->value);
	}

	public function isNull () {
		return $this->value == NULL;
	}
}