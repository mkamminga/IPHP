<?php
namespace IPHP\Model;

class HasOne extends ModelRelation {
	protected $keys = [];
	public function injectIntoCollection (array $collection = []){
		foreach ($collection as $model) {
			$key = $model->retreive($this->foreignKey);
			if ($key != null){
				$this->keys[$key][] = $model;
			}
		}

		$values = array_keys($this->keys);

		if (count($values) > 0) {

			$all = $this->model->get($this->model->select()->where(
				$this->where->in($this->primaryKey, $values)
			));

			if ($all){
				foreach ($all as $related) {
					$key = $related->retreive($this->primaryKey);

					if ($key != null && array_key_exists($key, $this->keys)) {
						foreach ($this->keys[$key] as $model) {
							$model->setRelated($this->name, $related);
						}
					}
				}
			}
		}
	}
}