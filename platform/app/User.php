<?php
namespace App;

use IPHP\Model\Model;
use IPHP\Model\HasMany;

class User extends Model {
	protected $table = 'users';
	protected $primaryKeys = ['id'];

	public function orders () {
		return new HasMany(new FirstModel, 'Users_id', 'id', 'orders');
	}

	public function all () {
		return $this->select();
	}
}