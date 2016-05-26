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
		'num'		=> 'validateNum',
		'email' 	=> 'validateEmail',
		'min' 		=> 'validateMinSize',
		'max' 		=> 'validateMaxSize',
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
				//extracts extra params from rules (size:... -> size and an array of bounds)
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
		$replaceable = [];

		foreach ($bounds as $key => $value) {
			$replaceable[':' . $key] = $value;
		}

		$replaceable[':field'] = $fieldname;

		return str_replace(array_keys($replaceable), array_values($replaceable), $rawMessage);

	}

	public function hasPassed ():bool {
		return empty($this->errors);
	}

	public function getErrors ():array {
		return $this->errors;
	}

	public function addError (string $key, string $error) {
		$this->errors[$key] = $error;
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

	private function validateNum ($data):bool {
		return preg_match('/^[0-9]+$/', $data);
	}

	private function validateMinSize ($data, array $bounds): bool{
		return (isset($bounds['size']) && strlen($data) >  (int)$bounds['size']);
	}

	private function validateMaxSize ($data, array $bounds): bool{
		return (isset($bounds['size']) && strlen($data) < (int)$bounds['size']);
	}
}