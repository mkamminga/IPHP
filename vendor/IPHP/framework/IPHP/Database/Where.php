<?php
namespace IPHP\Database;

class Where extends Clause {
	public function getComputedQuery () {
		if (!empty($this->clause)) {
			return ' WHERE ' . $this->clause;
		}
	}
}