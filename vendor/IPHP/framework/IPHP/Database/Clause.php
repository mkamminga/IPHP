<?php
namespace IPHP\Database;
use IPHP\Database\QueryTraits\WhereQueriable;

class Clause extends Queriable {
	protected $clause = '';
	protected $operators = [
		'=',
		'!=',
		'<=',
		'>=',
		'>',
		'<'
	];

	protected $isValueOperators = [
		'NOT',
		'IS NULL',
		'IS NOT NULL'
	];

	protected function append (string $andOr, string $query, array $params = []) {
		if (!empty($this->clause)){
			$this->clause.= ' ' . (($andOr == 'AND' || $andOr == 'OR') ? $andOr : 'AND') . ' ';
		}

		$this->clause.= $query;

		foreach ($params as $param) {
			$this->values[] = $param;
		}
	}

	public function is ($field, $operator, $value = NULL, $andOr = 'AND') {
		if (in_array($operator, $this->operators)) {
			$assignedOperatorValue = ' ' . $operator . ' ?';
		} else {
			$assignedOperatorValue = ' = ?';
		}

		$sql = $field . $assignedOperatorValue;

		$param = [];
		if ($value !== NULL){
			$param = [$value];
		}

		$this->append($andOr, $sql, $param);

		return $this;
	}

	public function isNull ($field, $andOr = 'AND') {
		$this->append($andOr, $field . ' IS NULL');

		return $this;
	}

	public function notIsNull ($field, $andOr = 'AND') {
		$this->append($andOr, $field . ' IS NOT NULL');
		
		return $this;
	}

	public function equals ($field, $value, $andOr = 'AND') {
		$this->append($andOr, $field . ' = ?', [$value]);

		return $this;
	}

	public function in ($field, array $values, $andOr = 'AND') {
		if (count($values) > 0){
			$this->append($andOr, $field . ' IN (?'. (count($values) > 1 ? str_repeat(',?', count($values) -1) : '') .')', $values);
		} else {
			throw new \Exception("In requires more than one value!");
		}
		return $this;
	}

	public function inSelect ($field, Queriable $query, $andOr = 'AND') {
		$this->append($andOr, $field . ' IN ('. $query->getComputedQuery() .')', $query->getValues());

		return $this;
	}

	public function clause (Clause $query, $andOr = 'AND') {
		$appendData = $query->getComputedQuery();
		if ($appendData){
			$this->append($andOr, '('. $query->getComputedQuery() . ')', $query->getValues());
		}

		return $this;
	}

	public function raw ($query, array $params = []) {
		$this->whereClause.= $query;

		foreach ($params as $param) {
			$this->whereValues[] = $param;
		}

		return $this;
	}

	public function getComputedQuery () {
		return $this->clause; 
	}
}