<?php
namespace IPHP\Database;
use IPHP\Database\QueryTraits\QueriableHelpers;

abstract class Queriable {
	protected $values = [];

	public abstract function getComputedQuery();
	public function getValues () {
		return $this->values;
	}
}