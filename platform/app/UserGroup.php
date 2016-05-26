<?php
namespace App;

use IPHP\Database\Where;
use IPHP\Model\Model;
use IPHP\Model\HasMany;

class UserGroup extends Model {
	protected $table = 'groups';
	protected $primaryKeys = ['id'];
}