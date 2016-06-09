<?php
namespace App;

use IPHP\Model\Model;

class OrderState extends Model {
    protected $table = 'order_states';
    protected $primaryKeys = ['name'];
    protected $ai = false;
}