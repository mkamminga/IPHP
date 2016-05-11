<?php
namespace IPHP\Http;

class Response {
	protected $headers = [];
	protected $statusCode = 200;
	protected $body;

	public function setBody($body = '') {
		$this->body = $body;
	}

	public function addHeader ($name, $content) {
		$this->headers[] = [
			$name => $content
		];
	}

	public function setStatusCode ($code) {
		$this->statusCode = $code;
	}

	public function send () {
		$this->sendHeaders();
		http_response_code($this->statusCode);
		print($this->body);
	}

	private function sendHeaders () {
		foreach ($this->headers as $row) {
			list($name, $data) = each($row); 
			header($name, $data);
		}
	}
}