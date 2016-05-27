<?php
namespace IPHP\Helpers\View;

class Messages {
	private $successClass = 'success';
	private $waringClass 	= 'warning';
	private $errorClass 	= 'errors';

	public function errorClass ($class) {
		$this->errorClass = $class;
	}

	public function succesClass ($class) {
		$this->successClass = $class;
	}

	public function warningClass ($class) {
		$this->waringClass = $class;
	}

	public function errors (array $errors):string {
		$output = '';
		if (count($errors) > 0){
			$output = "<div class=\"". $this->errorClass ."\">\n\t<ul>\n\t";
	               
	        foreach ($errors as $error):
	        	$output.= "<li>". $error ."</li>\n";
			endforeach;
	        
	        $output.= "</ul>\n</div>\n";
	    }

        return $output;
	}

	public function success ($title, $message):string {
		return "";
	}

	public function warning ($title, $message):string {
		return "";
	}
}