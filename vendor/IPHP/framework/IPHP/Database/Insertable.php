<?php
namespace IPHP\Database;

use IPHP\Database\QueryTraits\ModifyQueriable;

class Insertable extends Queriable {
	use ModifyQueriable;

	protected $modus = 'INSERT';
	protected $table;

	public function __construct ($table) {
		$this->table = $table;
	}

	public function modus (string $modus) {
		$modus = strtoupper($modus);
		if ($modus == 'INSERT' || $modus == 'REPLACE') {
			$this->modus = $modus;
		}

		return $this;
	}

	public function getComputedQuery () {
		$query =  $this->modus . ' INTO '. $this->table . ' ('. implode(',', array_keys($this->fields)) .') VALUES(?'. str_repeat(',?', count(array_keys($this->fields)) - 1) .')';

		$this->values = array_values($this->fields);

		return $query; 
	}
}