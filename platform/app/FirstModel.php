<?php
namespace App;

use IPHP\Model\Model;
use IPHP\Model\HasOne;
use IPHP\Database\Clause;
use IPHP\Database\Where;
use IPHP\Database\Having;

class FirstModel extends Model {
	protected $table = 'orders';
	protected $primaryKeys = ['id'];

	public function all () {
		$select = $this->select();
					//->where((new Where())->InSelect('username', $this->select('username')->where(new Where()->equals('id', 1))->is('address', '=', 'HAaa'))
					//);

		return $select;
	}

	public function join () {
		$join = $this->select('users.*')
					->innerJoin('orders', 'orders.Users_id', '=', 'users.id')
					->where((new Where())->is('users.id', '>', 1))
					->orderBy('users.id')
					->orderBy('firstname');

		return $this->get($join);
	}

	public function user () {
		return new HasOne(new User, 'Users_id', 'id', 'user');
	}
}