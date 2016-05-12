<?php
namespace IPHP\Database;
use IPHP\Database\QueryTraits\WhereQueriable;
use IPHP\Database\QueryTraits\ModifyQueriable;

class Updateable extends Clauseable {
	use ModifyQueriable;
	protected $table;
	protected $fields = [];

	public function __construct ($table) {
		$this->table = $table;
	}
	
	public function getComputedQuery () {
		$values = [];
		$query =  'UPDATE '. $this->table . ' SET ';

		$fields = '';
		foreach ($this->fields as $field => $value) {
			if (!empty($fields)) {
				$fields.= ', ';
			}

			$fields.= $field . ' = ?';

			$values[] = $value;
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