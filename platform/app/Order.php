<?php
namespace App;

use IPHP\Model\Model;

class Order extends Model {
    protected $table = 'orders';
    protected $primaryKeys = ['id'];
}