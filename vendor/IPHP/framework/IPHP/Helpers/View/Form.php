<?php
namespace IPHP\Helpers\View;

class Form {
	public function input (string $type, string $name, $value, array $attr = []):string {
		$input = '<input type="'. $type .'" name="'. $name .'" value="'. $value .'"';

		if (!empty($attr)) {
			$input.= $this->parseAttrs($attr);
		}

		$input.= ">\n";

		return $input;
	}

	private function parseAttrs (array $attrs) {
		$parsedAttrs = [];

		foreach ($attrs as $key => $value) {
			if (!is_array($value)){
				$parsedAttrs[] = $key . '="'. $value .'"';
			} else {
				foreach ($value as $subkey => $subvalue) {
					$parsedAttrs[] = $key . '-'. $subkey .'="'. $subvalue .'"';
				}
			}
		}


		return implode(' ', $parsedAttrs);

	}

	public function text (string $name, $value, array $attr = []) {
		return $this->input('text', $name, $value, $attr);
	}

	public function password (string $name, $value, array $attr = []) {
		return $this->input('password', $name, $value, $attr);
	}

	public function select (string $name, array $values, $selected = '', array $attr = []) {

		$output = '<select name="'. $name .'"';
		if (!empty($attr)) {
			$output.= $this->parsedAttrs($attr);
		}

		$output.='>'. chr(13) . chr(9);

		if (count($values)) {
			$output.= '<option value="">-</option>' . chr(13);
			foreach ($values as $key => $value) {
				$output.= '<option value="'. $key.'"';

				if ($key == $selected) {
					$output.= ' selected="selected"';
				}

				$output.='>'. $value .'</option>' . chr(13);
			}
		}

		$output.='</select>'. chr(13);

		return $output;
	}

	public function upload (string $name, array $attr = []) {
		return $this->input('file', $name, '', $attr);
	}

	public function imageupload (string $name, array $attr = []) {
		if (!isset($attr['accept'])) {
			$attr['accept'] = "image/*";
		}

		return $this->input('file', $name, '', $attr);
	}
}