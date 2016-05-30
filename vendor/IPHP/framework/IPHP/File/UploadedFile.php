<?php
namespace IPHP\File;

class UploadedFile {
	private $tmp_name;
	private $name;
	private $size;
	private $error;

	public function __construct (array $data) {
		$fields = ['tmp_name', 'name', 'size', 'error'];
		foreach ($fields as $name) {
			if (!isset($data[$name])) {
				throw new \Exception("Field '". $name ."' not set!");
			}
		}
		$this->tmp_name = $data['tmp_name'];
		$this->name 	= $data['name'];
		$this->size 	= $data['size'];
		$this->error 	= $data['error'];
	}

	public function getName ():string {
		return $this->name;
	}

	public function setName (string $name) {
		$this->name = $name;
	}

	public function getSize () {
		return $this->size;
	}

	public function getError () {
		return $this->error;
	}

	public function move (string $dir, string $name):bool {
		return $this->error == UPLOAD_ERR_OK
				&& file_exists($this->tmp_name) 
				&& move_uploaded_file($this->tmp_name, $dir . DIRECTORY_SEPARATOR . $name);
	}
}