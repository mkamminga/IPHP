<?php
namespace IPHP\App;

class ReflectorService {
	private $app;
	private $namedVars = [];

	public function __construct (ServiceManager $app) {
		$this->app = $app;
	}

	public function registerNamedVars ($name, $value) {
		$this->namedVars[$name] = $value;
	}

	public function unregisterNamedVars ($name) {
		if (array_key_exists($name, $this->namedVars)){
			unset($this->namedVars[$name]);
		}
	}

	public function hasInstance ($className) {
		return $this->app->hasService($className);
	}

	public function getService ($className) {
		return $this->app->getService($className);
	}

	public function createInstance ($className, array $namedParams = []) {

		if ($this->hasInstance($className)) {
			return $this->getService($className);
		} else {

			$reflectionClass = new \ReflectionClass($className);

			$constructor 	= $reflectionClass->getConstructor();
			$params = [];
			if ($constructor) {
				$params = $constructor->getParameters();
			}

			return $reflectionClass->newInstanceArgs($this->extractArgs($params, $namedParams));
		}		
	}

	public function callMethod ($classInstance, $method, array $namedParams = []) {
		$reflection = new \ReflectionMethod($classInstance, $method);

		return $reflection->invokeArgs($classInstance, $this->extractArgs($reflection->getParameters(), $namedParams));
	}

	private function extractArgs (array $params = [], array $namedParams = []) {
		$args = [];
		foreach ($params as $i => $param) {
			if ($param->getClass()){
				if ($param->isOptional() && !$this->hasInstance($param->getClass()->name)){
					$args[] = $param->getDefaultValue();
				} else {
		    		$args[] = $this->createInstance($param->getClass()->name);
		    	}
			} else if (array_key_exists($param->getName(), $namedParams)){
				$args[] = $namedParams[$param->getName()];
			} else if (array_key_exists($param->getName(), $this->namedVars)){
				$args[] = $this->namedVars[$param->getName()];
			}else if ($param->isOptional()) {
				$args[] = $param->getDefaultValue();
			} else {
				$args[] = NULL;
			}
		}

		return $args;
	}
}