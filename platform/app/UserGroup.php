<?php
namespace App;

use IPHP\Database\Where;
use IPHP\Model\Model;

class UserGroup extends Model {
	protected $table = 'groups';
	protected $primaryKeys = ['id'];

	public function getByName ($name) {
		return $this->getOne(
			$this->select()->where(
				(new Where)->equals('name', $name)
			)->limit(1)
		);
	}
}