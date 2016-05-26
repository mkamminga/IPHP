<?php
namespace IPHP\Model;

class BelongsTo extends HasOne {
	public function __construct (Model $model, $primaryKey, $foreignKey, $name) {
		parent::__construct($model, $foreignKey, $primaryKey, $name);
	}
}