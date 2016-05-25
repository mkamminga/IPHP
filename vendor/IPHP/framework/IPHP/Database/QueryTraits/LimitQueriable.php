<?php
namespace IPHP\Database\QueryTraits;

use IPHP\Database\Selectable;
use IPHP\Database\Queriable;
use IPHP\Database\Clause;

trait LimitQueriable {
	private $limit = NULL;
	private $offset = NULL;
	

	public function limit (int $number) {
		$this->limit = $number;

		return $this;
	}

	public function offset (int $offset) {
		$this->offset = $offset;

		return $this;
	}

	protected function computeLimit (&$query) {
		if ($this->limit !== NULL){
			$query.= ' LIMIT '. $this->limit;

			if ($this->offset != NULL) {
				$query.= ' OFFSET '. $this->offset;
			}
		}
	}
}