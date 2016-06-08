<?php
namespace App;

use IPHP\Model\Model;
use IPHP\Database\Clause;
use IPHP\Database\Where;
use IPHP\Database\Having;

class OrderRows extends Model {
	protected $table = 'orderrows';
	protected $primaryKeys = ['Orders_id', 'Products_id'];
	protected $ai = false;

	public function all () {
		$select = $this->select('username', 'address');
					//->where((new Where())->InSelect('username', $this->select('username')->where(new Where()->equals('id', 1))->is('address', '=', 'HAaa'))
					//);

		return $this->getOne($select);
	}

	public function join () {
		$join = $this->select('users.*')
					->innerJoin('orders', 'orders.Users_id', '=', 'users.id')
					->where((new Where())->is('users.id', '>', 1))
					->orderBy('users.id')
					->orderBy('firstname');

		return $this->get($join);
	}
}