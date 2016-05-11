<?php
namespace IPHP\Database;
use IPHP\Database\QueryTraits\WhereQueriable;
use IPHP\Database\QueryTraits\JoinQueriable;

abstract class Clauseable extends Queriable {
	protected $where;
	protected $having;

	public function where (Where $clause) {
		$this->where = $clause;

		return $this;
	}

	public function having (Having $clause) {
		$this->having = $clause;

		return $this;
	}

	protected function appendClauseTo (Clause $clause, string &$appendQueryTo, array &$appendValuesTo = []) {
		$query = $clause->getComputedQuery();
		if (!empty($query)) {
			$appendQueryTo.= $query;
			$values = $clause->getValues();

			foreach ($values as $param) {
				$appendValuesTo[] = $param;
			}
		}
	}
}