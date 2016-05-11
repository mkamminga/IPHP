<?php
namespace IPHP\Database;
use IPHP\Database\QueryTraits\WhereQueriable;

class Updateable extends Clauseable {

	protected $table;
	protected $fields = [];

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

		return $this;
	}

	public function getComputedQuery () {
		$values = [];
		$query =  'UPDATE '. $this->table . ' SET ';

		$fields = '';
		foreach ($this->fields as $field) {
			if (!empty($fields)) {
				$fields.= ', ';
			}

			$fields.= $field . ' = ?';
		}

		$query.= $fields;

		//Append where clause to query, if isset
		if ($this->where){
			$this->appendClauseTo($this->where, $query, $values);
		}

		$this->values = $values;

		return $query; 
	}
}