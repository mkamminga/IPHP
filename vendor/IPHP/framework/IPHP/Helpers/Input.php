<?php
namespace IPHP\Helpers;

use IPHP\Http\Request;
use IPHP\Model\Model;

class Input {
	private $request;
	private $model = NULL;
	public function __construct (Request $request) {
		$this->request = $request;
	}

	public function setModel (Model $model) {
		$this->model = $model;
	}

	public function get (string $name) {
		$data = $this->retreive($name);

		if ($data != NULL) {
			$data = filter_var($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}

		return $data;
	}

	public function raw (string $name) {
		return $this->retreive($name);
	}

	public function escaped (string $name) {
		$data = $this->retreive($name);
		if ($data == NULL) {
			return $data;
		}

		if (function_exists('mb_detect_encoding') && function_exists('iconv')){
			$encoding = mb_detect_encoding($data);

			if ($encoding && $encoding != "UTF-8"){
				$data = iconv($encoding, "UTF-8//IGNORE", $data);
			}
		} else if (function_exists('utf8_encode')) {
			$data = utf8_encode($data);
		}
		
		$result = preg_replace_callback('/[^a-z0-9,\.\-_]/iSu', function ($matches) {
			$chr = $matches[0];
	        $ord = ord($chr);
			//replace unknown chars with nothing
	        if (($ord <= 0x1f && $chr != "\t" && $chr != "\n" && $chr != "\r")
	            || ($ord >= 0x7f && $ord <= 0x9f)
	        ) {
	            return '';
	        }

	        $hex = bin2hex($chr);
	        $ord = hexdec($hex);

			$allowed = [
		        34 => 'quot',       
		        38 => 'amp',          
		        60 => 'lt',           
		        62 => 'gt',           
		    ];

	        if (isset($allowed[$ord])) {
	            return '&' . $allowed[$ord] . ';';
	        }

	        if ($ord > 255) {
	            return sprintf('&#x%04X;', $ord);
	        }
	        return sprintf('&#x%02X;', $ord);
		}, $data);
		
		return $result;
    }

    private function retreive ($name) {
    	if ($this->model != NULL && !in_array($this->request->getMethod(), ['post', 'put', 'delete'])) {
    		return $this->model->retreive($name);
    	}

    	$data = $this->request->get($name);
    	if ($data) {
    		return $data->getValue();
    	}

    	return NULL;
    }
}