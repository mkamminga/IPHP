<?php
namespace IPHP\Database\QueryTraits;

trait GroupByQueriable {
	protected $groupBy = [];
	public function groupBy (string $field) {
		$this->groupBy[] = $field;
	}

	protected function computeGroupBy (&$query) {
		if (!empty($this->groupBy)) {
			$query.= ' GROUP BY ' . implode(',', $this->groupBy);
		}
	}
}