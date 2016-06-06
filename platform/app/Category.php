<?php
namespace App;

use IPHP\Model\Model;
use IPHP\Model\HasMany;
use IPHP\Database\Selectable;
use IPHP\Database\Where;

class Category extends Model {
	protected $table = 'categories';
	protected $primaryKeys = ['id'];
	protected $softDelete = true;

	public function all () {
		return $this->select();
	}

	public function byName (Selectable $selectable) {
		$selectable->orderBy('categories.name');

		return $selectable;
	}

	public function allWithParent () {
		return $this->select('categories.*', 'main.name AS main', 'main.id as main_parent_id')
					->leftJoin('categories AS main', 'main.id', '=', 'categories.Parent_id');
	}

	public function allParent () {
		return $this->select()
					->where((new Where)->isNull('Parent_id'));
	}
	
	public function allFromParent (int $id) {
		return $this->select()
					->where((new Where())->equals('categories.Parent_id', $id));
	}
	
	public function relatedSubCategories () {
		return new HasMany(new Category, 'Parent_id', 'id', 'subCategories');
	}
}