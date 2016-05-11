<?php
namespace IPHP\Database;
use IPHP\Database\QueryTraits\JoinQueriable;
use IPHP\Database\QueryTraits\OrderByQueriable;
use IPHP\Database\QueryTraits\GroupByQueriable;
use IPHP\Database\QueryTraits\LimitQueriable;

class Selectable extends Clauseable {
	use JoinQueriable, OrderByQueriable, GroupByQueriable, LimitQueriable;

	protected $selectFields = [];
	protected $table;

	public function __construct ($table) {
		$this->table = $table;
	}

	public function select (array $fields) {
		foreach ($fields as $field) {
			$this->selectFields[] = $field;
		}

		return $this;
	}

	public function getComputedQuery () {
		$values = [];
		$query =  'SELECT ';

		if (empty($this->selectFields)) {
			$query.= '*';
		} else {
			$fields = '';
			foreach ($this->selectFields as $fieldOrQuery) {
				if ($fieldOrQuery instanceof Queriable) {
					$value = '(' . $fieldOrQuery->getComputedQuery() . ')';
				} else {
					$value = $fieldOrQuery;
				}

				$fields.= (empty($fields) ? $value : ', '. $value);
			}

			$query.= $fields;
		}

		$query.= ' FROM '. $this->table;
		$this->computeJoin($query);
		//Append where clause to query, if isset
		if ($this->where){
			$this->appendClauseTo($this->where, $query, $values);
		}
		$this->computeGroupby($query);
		//same as where clause
		if ($this->having) {
			$this->appendClauseTo($this->having, $query, $values);
		}
		$this->computeOrderby($query);
		$this->computeLimit($query);

		$this->values = $values;

		return $query; 
	}
}