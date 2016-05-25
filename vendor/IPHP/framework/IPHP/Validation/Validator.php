<?php
namespace IPHP\Validation;

use IPHP\Translation\Translator;

class Validator {
	private $translator;
	private $ruleValidator;
	private $errors = [];
	private $rules = [];
	protected $requirementToMethodMap = [
		'required' 	=> 'validateRequired',
		'alpha_num' => 'validateAlphaNum',
		'email' 	=> 'validateEmail',
		'min' 		=> 'validateMin',
		'max' 		=> 'ValidateMax'
	];

	public function __construct (Translator $translator) {
		$this->translator = $translator;
	}

	public function addRules (array $rules) {
		foreach ($rules as $rule) {
			$this->rules[] = $rule;
		}
	}

	public function validate (array $data = []):bool {
		foreach ($this->rules as $rule) {
			$inputName 	= $rule->getInputname();
			$methods 	= $rule->getRequirements();
			$value 		= isset($data[$inputName]) ? $data[$inputName] : NULL;

			foreach ($methods as $method) {
				$bounds = [];
				$methodFromRule = '';
				$this->setRuleAndBounds($method, $methodFromRule, $bounds);

				if (isset($this->requirementToMethodMap[$methodFromRule]) && method_exists($this, $this->requirementToMethodMap[$methodFromRule])) {

					$realMethod = $this->requirementToMethodMap[$methodFromRule];
					if (!$this->{$realMethod}($value, $bounds)) {
						$this->errors[$inputName] = $this->parseMessage($methodFromRule, $rule->getFieldname(), $bounds);	

						break;
					}
				} else {
					throw new \Exception("No method found for rule: ". $methodFromRule);
				}
			}
		}

		return $this->hasPassed();
	}

	private function setRuleAndBounds ($rule, &$method, &$bounds) {
		$data 	= explode(':', $rule);
		if (count($data) > 1) {
			var_dump($data);
			$settings = explode('|', $data[1]);
			foreach ($settings as $setting) {
				list($key, $value) = explode('=', $setting);

				$bounds[$key] = $value;
			}
		} 

		$method = $data[0];
	}

	private function parseMessage ($key, $fieldname, $bounds):string {
		$rawMessage = $this->translator->get('validator', $key);
		array_walk($bounds, function ($first, &$key) {
			$key = ':'. $key;
		});

		$bounds[':field'] = $fieldname;

		return str_replace(array_keys($bounds), array_values($bounds), $rawMessage);

	}

	public function hasPassed ():bool {
		return empty($this->errors);
	}

	public function getErrors ():array {
		return $this->errors;
	}

	private function validateRequired ($data):bool {
		return !empty($data);
	}

	private function validateEmail ($email):bool {
		//
		//
		return false;
	}

	private function validateAlphaNum ($data):bool {
		return preg_match('/^[a-zA-Z0-9_\.\@]+$/', $data);
	}
}