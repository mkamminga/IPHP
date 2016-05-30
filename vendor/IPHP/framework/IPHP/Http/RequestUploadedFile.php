<?php
namespace IPHP\Http;

class RequestUploadedFile extends RequestInput {
	public function realName () {
		return (isset($this->value['name']) ? $this->value['name'] : NULL);
	}

	public function tmpName () {
		return (isset($this->value['tmp_name']) ? $this->value['tmp_name'] : NULL);
	}

	public function isEmpty() {
		return $this->isNull();
	}

	public function isNull () {
		return !isset($this->value['tmp_name']) || (isset($this->value['error']) && $this->value['error'] == UPLOAD_ERR_NO_FILE);
	}
}