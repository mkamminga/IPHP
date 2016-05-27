<?php
namespace IPHP\Helpers;

class Redirect {
	private $url;
	public function __construct (Url $url) {
		$this->url = $url;
	}

	public function to (string $url) {
		header('Location: '. $url);
		exit;
	}

	public function toRoute ($name, array $params = []) {
		$this->to($this->url->route($name, $params));
	}
}