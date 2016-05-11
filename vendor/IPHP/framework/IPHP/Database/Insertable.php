<?php
namespace IPHP\Database;

class Insertable extends Queriable {
	protected $fields = [];
	protected $values = [];
	protected $modus = 'INSERT';
	protected $table;

	public function __construct ($table) {
		$this->table = $table;
	}

	public function fields (array $fields) {
		foreach ($fields as $field) {
			$this->fields[] = $field;
		}

		return $this;
	}

	public function values (array $values) {
		$this->values = array_merge($this->values, $values);
	}

	public function addValue ($value) {
		$this->values[] = $value;
	}

	public function modus (string $modus) {
		if ($modus == 'INSERT' || $modus == 'REPLACE') {
			$this->modus = $modus;
		}

		return $this;
	}

	public function getValues () {
		return $this->values;
	}

	public function getComputedQuery () {
		$query =  $this->modus . ' INTO '. $this->table . ' ('. implode(',', $this->fields) .') VALUES(?'. str_repeat(',?', count($this->fields) - 1) .')';

		return $query; 
	}
}