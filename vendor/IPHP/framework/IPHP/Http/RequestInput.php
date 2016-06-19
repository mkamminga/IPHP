<?php
namespace IPHP\Http;

class RequestInput {
	protected $value;
	protected $name;
	protected $from;
	protected $flattend = [];

	public function __construct ($name, $mixedValue, $from) {
		$this->value = $mixedValue;
		$this->name = $name;
		$this->from = $from;

		if ($this->is('string')){
			$this->value = trim($this->value);
		}

		if (!$this->isEmpty() && $this->is('array')) {
			$this->flatten($mixedValue);
		}
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
		if (is_numeric($this->value)) {
			return 'integer';
		}
		return getType($this->value);
	}
	
	public function isEmpty () {
		if ($this->is('integer')) {
			return false;
		} 
		return empty($this->value);
	}

	public function isNull () {
		return $this->value == NULL;
	}

	public function flattend () {
		return $this->flattend;
	}

	private function flatten (array $values) {
	
		foreach ($values as $key => $value) {
			$inputName = $this->name . '.'. $key;
			
			$input = new RequestInput($inputName, $value, $this->from);
			$this->flattend[$inputName]  = $input;

			if ($input->is('array')) {
				$this->flattend = array_merge($this->flattend, $input->flattend);
			}
		}
	}
}