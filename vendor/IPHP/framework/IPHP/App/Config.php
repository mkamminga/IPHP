<?php
namespace IPHP\App;

class Config {
	private $data = [];

	public function __construct (array $data = []) {
		$this->data = $data;
	}

	public function getValue ($key) {
		return (array_key_exists($key, $this->data) ? $this->data[$key] : NULL);
	}

	public function data () {
		return $this->data;
	}

	public function setValue ($key, $value, $override = true) {
		if (!array_key_exists($key, $this->data) || $override) {
			$this->data[$key] = $value;
		}
	}

	public function hasKey ($key) {
		return array_key_exists($key, $this->data);
	}

	public function remove ($key) {
		if (array_key_exists($key, $this->data)) {
			unset($this->data[$key]);
		}
	}

	public function appendToArray ($key, array $data) {
		if (array_key_exists($key, $this->data) && is_array($this->data[$key])) {
			foreach ($data as $row) {
				$this->data[$key][] = $row;
			}
		}
	}

	public function merge ($key, array $data) {
		if (array_key_exists($key, $this->data) && is_array($this->data[$key])) {
			$this->data[$key] = array_merge($this->data[$key], $data);
		}
	}

}