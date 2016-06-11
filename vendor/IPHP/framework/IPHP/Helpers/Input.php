<?php
namespace IPHP\Helpers;

use IPHP\Http\Request;
use IPHP\Model\Model;

class Input {
	private $request;
	private $model = NULL;
	private $encodeIfNot = '/[^a-z0-9,\.\-_ÄäèÈëËéÉÆçÇÈèÖβ€Ññ]/iSu';
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

		$data = $this->toUTF8($data);
		

		$result = preg_replace_callback($this->encodeIfNot, function ($matches) {
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

	public function encode ($data) {
		if ($data && !empty($data)) {
			$data = urldecode($data);
			$data = $this->toUTF8($data);

			$data = preg_replace_callback($this->encodeIfNot, function ($matches) {
				$char = $matches[0];
				if (function_exists('mb_encode_numericentity')){
					$o = ord($char);
					if ( (strlen($char) > 1) || /* multi-byte [unicode] */
						($o <32 || $o > 126) || /* <- control / latin weirdos -> */
						($o >33 && $o < 40) ||/* quotes + ambersand */
						($o >59 && $o < 63) /* html */
					) {
						return mb_encode_numericentity($char, [0x0, 0xffff, 0, 0xffff], 'UTF-8');
					}
				}

				//default
				return htmlspecialchars($char, ENT_QUOTES | ENT_HTML5 | ENT_IGNORE, "UTF-8");
			}, $data);
		}

		return $data;
	}

	public function toUTF8 ($data) {
		if (function_exists('mb_detect_encoding') && function_exists('iconv')){
			$encoding = mb_detect_encoding($data);

			if ($encoding && $encoding != "UTF-8"){
				$data = iconv($encoding, "UTF-8//IGNORE", $data);
			}
		} else if (function_exists('utf8_encode')) {
			$data = utf8_encode($data);
		}

		return $data;
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