<?php
namespace IPHP\Database\QueryTraits;

trait JoinQueriable {
	protected $joinClause = '';
	protected $allowedJoinOperators = ['=', '!='];

	private function appendJoin (string $query) {
		if (!empty($this->joinClause)){
			$this->joinClause.= ' ';
		}

		$this->joinClause.= $query;
	}

	public function leftJoin ($table, $leftField, $operator, $rightField) {
		$this->appendJoin(
			'LEFT OUTER JOIN '. $table . ' ON '. $leftField . ' '. $this->getOperator($operator) . ' '. $rightField
		);

		return $this;
	}

	public function innerJoin ($table, $leftField, $operator, $rightField) {
		$this->appendJoin(
			'INNER JOIN '. $table . ' ON '. $leftField . ' '. $this->getOperator($operator) . ' '. $rightField
		);

		return $this;
	}

	public function rightJoin ($tableRight, $leftField, $operator, $rightField) {
		$this->appendJoin(
			'RIGHT JOIN '. $tableRight . ' ON '. $leftField . ' '. $this->getOperator($operator) . ' '. $rightField
		);

		return $this;
	}

	private function getOperator ($operator) {
		if (!in_array($operator, $this->allowedJoinOperators)) {
			return $operator;
		} 

		return '=';
	}

	protected function computeJoin (&$query) {
		if (!empty($this->joinClause)) {
			$query.= ' ' .$this->joinClause;
		}
	}
}