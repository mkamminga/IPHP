<?php
namespace IPHP\Database;

class Having extends Clause {
	public function getComputedQuery () {
		if (!empty($this->clause)) {
			return ' HAVING ' . $this->clause;
		}
	}
}