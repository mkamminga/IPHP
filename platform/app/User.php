<?php
namespace App;

use IPHP\Model\Model;

class User extends Model {
	protected $table = 'users';
	protected $primaryKeys = ['id'];
}