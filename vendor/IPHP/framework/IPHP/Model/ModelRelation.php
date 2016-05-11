<?php
namespace IPHP\Model;

use IPHP\Database\Where;

abstract class ModelRelation {
	protected $model;
	protected $primaryKey;
	protected $foreignKey;
	protected $name;
	protected $where;

	public function __construct (Model $model, $foreignKey, $primaryKey, $name) {
		$this->model = $model;
		$this->primaryKey = $primaryKey;
		$this->foreignKey = $foreignKey;
		$this->name = $name;
		$this->where = new Where;
	}

	public function where () {
		return $this->where;
	}

	public abstract function injectIntoCollection (array $collection = []);
}