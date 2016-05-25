<?php
namespace IPHP\App\Kernel;
use IPHP\App\ServiceManager;

abstract class Kernel {
	protected $app;

	public function __construct (ServiceManager $app) {
		$this->app = $app;
	}

	public abstract function resolve();
}