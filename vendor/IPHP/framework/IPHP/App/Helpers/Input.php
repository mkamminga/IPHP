<?php
namespace IPHP\App\Helpers;

use IPHP\Http\Request;

class Input {
	private $request;
	public function __construct (Request $request) {
		$this->request = $request;
	}

	public function get (string $name) {
		$data = $this->request->get($name);

		if ($data != NULL) {
			$data = filter_var($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}

		return $data;
	}

	public function raw (string $name) {
		return $this->request->get($name);
	}

	public function escaped (string $name) {
		$data = $this->request->get($name);
		if ($data == NULL) {
			return $data;
		}

		$data = utf8_encode($data);
		$result = preg_replace_callback('/[^a-z0-9,\.\-_]/iSu', function ($matches) {
			$chr = $matches[0];
	        $ord = ord($chr);
	        /**
	         * The following replaces characters undefined in HTML with the
	         * hex entity for the Unicode replacement character.
	         */
	        if (($ord <= 0x1f && $chr != "\t" && $chr != "\n" && $chr != "\r")
	            || ($ord >= 0x7f && $ord <= 0x9f)
	        ) {
	            return '';
	        }

	        $hex = bin2hex($chr);
	        $ord = hexdec($hex);

			$allowed = [
		        34 => 'quot',         // quotation mark
		        38 => 'amp',          // ampersand
		        60 => 'lt',           // less-than sign
		        62 => 'gt',           // greater-than sign
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
}