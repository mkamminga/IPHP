<?php
namespace IPHP\Database\QueryTraits;

use IPHP\Database\Selectable;
use IPHP\Database\Queriable;
use IPHP\Database\Clause;

trait OrderByQueriable {
	private $orderBy = '';
	
	public function orderBy (string $field, string $sortby = 'ASC') {
		$sortby = strtoupper($sortby);
		if ($sortby != 'ASC' && $sortby != 'DESC') {
			$sortby = 'ASC';
		}

		if (!empty($this->orderBy)) {
			$this->orderBy.= ', ';
		}

		$this->orderBy.= $field . ' '. $sortby;

		return $this;
	}

	protected function computeOrderBy (&$query) {
		if (!empty($this->orderBy)) {
			$query.= ' ORDER BY '. $this->orderBy;
		}
	}
}