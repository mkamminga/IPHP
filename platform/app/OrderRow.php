<?php
namespace App;

use IPHP\Model\Model;
use IPHP\Model\BelongsTo;

class OrderRow extends Model {
	protected $table = 'orderrows';
	protected $primaryKeys = ['Orders_id', 'Products_id'];
	protected $ai = false;

	public function product () {
		return new BelongsTo(new Product, 'id', 'Products_id', 'product');
	}
}