<?php
namespace App;

use IPHP\Model\HasOne;
use IPHP\Model\Model;
use IPHP\Database\Where;
use IPHP\Database\Selectable;

class Product extends Model {
	protected $table = 'products';
	protected $primaryKeys = ['id'];
	protected $softDelete = true;
	
	public function all (): Selectable {
		return $this->select();	
	}
	
	public function withArtnr (Selectable $selectable, int $id): Selectable {
		return $selectable->where((new Where)->equals('artikelnr', $id));
	}
	
	public function fromCategory (int $categoryId): Selectable {
		return $this->select()
					->where((new Where)->equals('Categories_id', $categoryId));
	}

	public function searchable ($mixedValue): Selectable {
		return $this->select()
					->where(
						(new Where)->like('name', '%'. $mixedValue .'%')
									->like('short_description', '%'. $mixedValue .'%', 'OR')
					);	
	}

	public function category (): HasOne {
		return new HasOne(new Category, 'Categories_id', 'id', 'category');
	}

	public function vat (): HasOne {
		return new HasOne(new VatRate, 'vat_rate_id', 'id', 'vat');
	}
}