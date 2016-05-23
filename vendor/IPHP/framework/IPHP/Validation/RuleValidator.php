<?php
namespace IPHP\Validation;

class RuleValidator {
	public function valdate ($data, array $methods) {
		foreach ($methods as $method) {
			if (method_exists($this, $method)){
				$this->{$method}($data);
			} else {
				throw new \Exception;
			}
		}
	}
}