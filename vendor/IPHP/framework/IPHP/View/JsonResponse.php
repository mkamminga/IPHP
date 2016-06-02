<?php
namespace IPHP\View;

class JsonResponse extends Response {
	public function __construct (array $vars = []) {
		parent::__construct ($vars);
		
		$this->strategy 	= \IPHP\App\View\JsonStrategy::class;
		$this->compileable 	= false;
	}
}