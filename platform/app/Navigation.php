<?php
namespace App;

use IPHP\Database\Where;
use IPHP\Model\Model;
use IPHP\Model\HasMany;

class Navigation extends Model {
	protected $table = 'navigations';
	protected $primaryKeys = ['id'];

	public function allSorted () {
		return  $this->select()->orderBy('position');
	}
}