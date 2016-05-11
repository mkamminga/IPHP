<?php
namespace App;

use IPHP\Model\Model;

class Category extends Model {
	protected $table = 'categories';
	protected $primaryKeys = ['id'];
}