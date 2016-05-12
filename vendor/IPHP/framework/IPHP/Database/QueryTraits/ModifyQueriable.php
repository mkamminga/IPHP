<?php
namespace IPHP\Database\QueryTraits;

trait ModifyQueriable {
	protected $fields = [];
	public function fields (array $fields) {
		$this->fields = array_merge($this->fields, $fields);

		var_dump($this->fields);

		return $this;
	}
}