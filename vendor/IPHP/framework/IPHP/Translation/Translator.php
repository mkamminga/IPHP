<?php
namespace IPHP\Translation;

class Translator {
	private $language;
	private $translations = [];

	public function __construct ($lang) {
		$this->language = $lang;
	}

	public function getLang () {
		return $this->language;
	}

	public function set ($key, array $values) {
		$this->translations[$key] = $values;
	}

	public function has ($key, $name) {
		return isset($this->translations[$key][$name]);
	}

	public function get ($key, $name) {
		if ($this->has($key, $name)) {
			return $this->translations[$key][$name];
		}

		return '';
	}
}