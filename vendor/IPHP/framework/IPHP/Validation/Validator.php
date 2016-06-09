<?php
namespace IPHP\Validation;

use IPHP\Translation\Translator;
use IPHP\Http\RequestInput;
use IPHP\Http\RequestUploadedFile;

class Validator {
	private $translator;
	private $ruleValidator;
	private $errors = [];
	private $rules = [];
	protected $requirementToMethodMap = [
		'required' 		=> 'validateRequired',
		'alpha_num' 	=> 'validateAlphaNum',
		'num'			=> 'validateNum',
		'email' 		=> 'validateEmail',
		'min' 			=> 'validateMinSize',
		'max' 			=> 'validateMaxSize',
		'mime'			=> 'validateMimeType',
		'regex'			=> 'validateRegex'
	];

	protected $nonEscapeable = [
		'required',
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
			$valueSet 	= $value != NULL && !$value->isNull();
			
			foreach ($methods as $method) {
				$bounds = [];
				$methodFromRule = '';
				//extracts extra params from rules (size:... -> size and an array of bounds)
				$this->setRuleAndBounds($method, $methodFromRule, $bounds);

				if (isset($this->requirementToMethodMap[$methodFromRule]) && method_exists($this, $this->requirementToMethodMap[$methodFromRule])) {

					$realMethod = $this->requirementToMethodMap[$methodFromRule];
					if (!$valueSet && !in_array($methodFromRule, $this->nonEscapeable)) {
						continue;
					}
				

					if (!$this->{$realMethod}($value, $bounds)) {
						$rawMessage = '';
						if ($rule->hasMessageFor($methodFromRule)) {
							$rawMessage = $rule->getMessageFor($methodFromRule);
						}
						$this->errors[$inputName] = $this->parseMessage($methodFromRule, $rule->getFieldname(), $bounds, $rawMessage);	

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

	private function parseMessage ($key, $fieldname, $bounds, $rawMessage = ''):string {
		if (empty($rawMessage)){
			$rawMessage = $this->translator->get('validator', $key);
		}
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

	public function hasErrors ():bool {
		return !empty($this->errors);
	}

	private function validateRequired ($data):bool {
		return ($data && $data instanceof RequestInput && !$data->isEmpty());
	}
	
	private function validateRegex (RequestInput $data, $params = []):bool {
		if (!isset($params['expression'])) {
			throw new \Exception("Expression not set!");
		}
		
		return preg_match($params['expression'], $data->getValue());
	}

	private function validateEmail (RequestInput $email):bool {
		return filter_var($email->getValue(), FILTER_VALIDATE_EMAIL);
	}

	private function validateAlphaNum (RequestInput $data):bool {
		return preg_match('/^[a-zA-Z0-9_\.\@]+$/', $data->getValue());
	}

	private function validateNum (RequestInput $data):bool {
		return preg_match('/^[0-9]+$/', $data->getValue());
	}

	private function validateMinSize (RequestInput $input, array $bounds): bool{
		$data = $input->getValue();
		return (isset($bounds['size']) && strlen($data) >=  (int)$bounds['size']);
	}

	private function validateMaxSize (RequestInput $input, array $bounds): bool{
		$data = $input->getValue();
		return (isset($bounds['size']) && strlen($data) <= (int)$bounds['size']);
	}

	private function validateMimeType (RequestUploadedFile $file, array $bounds):bool {
		$data = $file->getValue();

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