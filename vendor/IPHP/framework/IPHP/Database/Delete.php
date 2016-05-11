<?php
namespace IPHP\Database;
use IPHP\Database\QueryTraits\JoinQueriable;

class Delete extends Clauseable {
	use JoinQueriable;

	protected $table;

	public function __construct ($table) {
		$this->table = $table;
	}

	public function getComputedQuery () {
		$values = [];
		$query =  'DELETE FROM '. $this->table;
		$this->computeJoin($query);
		//Append where clause to query, if isset
		if ($this->where){
			$this->appendClauseTo($this->where, $query, $values);
		}
	
		$this->values = $values;

		return $query; 
	}
}