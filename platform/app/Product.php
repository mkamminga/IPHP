<?php
namespace App;

use IPHP\Model\Model;
use IPHP\Database\Where;

class Product extends Model {
	protected $table = 'products';
	protected $primaryKeys = ['id'];

	public function fromCategory (int $categoryId) {
		return $this->select()
					->where((new Where)->equals('Categories_id', $categoryId))
					->orderBy('name');
	}
}