<?php
namespace App;

use IPHP\Database\Where;
use IPHP\Model\Model;
use IPHP\Model\BelongsTo;

class User extends Model {
	protected $table = 'users';
	protected $primaryKeys = ['id'];

	public function role () {
		return new BelongsTo(new UserGroup, 'id', 'group_id', 'role');
	}

	public function all () {
		return $this->select();
	}

	public function findByName ($name) {
		return $this->getOne(
			$this->select()
				 ->where((new Where)->equals('username', $name)->equals('active', 1))
				 ->limit(1)
		);
	}

	public function isUnique ($username):bool {
		return empty($this->findByName($username));
	}
}