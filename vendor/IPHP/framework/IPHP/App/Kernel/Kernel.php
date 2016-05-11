<?php
namespace IPHP\App\Kernel;
use IPHP\App\App;

abstract class Kernel {
	protected $app;

	public function __construct (App $app) {
		$this->app = $app;
	}

	public abstract function resolve();
}