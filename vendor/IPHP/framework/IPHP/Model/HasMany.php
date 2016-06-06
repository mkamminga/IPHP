<?php
namespace IPHP\Model;
use IPHP\Database\Where;

class HasMany extends ModelRelation {
	public function injectIntoCollection (array $collection = []){
		foreach ($collection as $model) {
			$key = $model->retreive($this->primaryKey);
			$where = (new Where)->equals($this->foreignKey, $key);
			if ($this->where) {
				$where->clause($this->where);
			}
			$all = $this->model->get($this->select->where(
				$where
			));
	
			if ($all){			
				$model->setRelatedCollection($this->name, $all);
			}
		}
	}
}