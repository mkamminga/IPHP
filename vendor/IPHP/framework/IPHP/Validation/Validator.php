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
		'mime'		=> 'validateMimeType'
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
		if (is_array($data) 
			&& isset($data['tmp_name']) 
			&& isset($data['name'])
			&& isset($data['error'])
			&& isset($data['type'])
			&& isset($data['size']))  {
			return $data['error'] == UPLOAD_ERR_OK;
		} else {
			return !empty($data);
		}
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

	private function validateMimeType ($data, array $bounds):bool {
		if (!is_array($data)) {
			return false;
		}

		$types = [];
		
		if (!isset($bounds['types']) || !isset($bounds['prefix'])) {
			throw new \Exception("Type of prefix not set");
		}

		$types = explode(',', $bounds['types']);
		foreach ($types as &$type) {
			$type = $bounds['prefix'] . '/' . $type;
		}
		//First mime type check of the uploaded file
		//least secure, but most simple and quick check
		if (!isset($data['type']) || !in_array($data['type'], $types)) {
			return false;
		}

		$finfo = finfo_open(FILEINFO_MIME);

		if (!$finfo) {
			throw new \Exception("Failed opening file info!");
		}

		$file = finfo_file($finfo, $data['tmp_name']);
		$matches = [];
		//couldn't extract mime\type
		if (!preg_match('/^([a-z0-9_]+\/[a-z0-9_]+)(?=\;)/', $file, $matches)){
			return false;
		}
		//receck mime type, more secure than the first mime check which relies on the defined mimetype
		//this check compares the extrated mimetype from the reading the file, againt allowed mimetypes
		if (!in_array($matches[0], $types)) {
			return false;
		}

		return true;
	}
}