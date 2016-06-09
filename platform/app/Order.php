<?php
namespace App;

use IPHP\Model\Model;
use IPHP\Model\HasMany;
use IPHP\Model\HasOne;

class Order extends Model {
    protected $table = 'orders';
    protected $primaryKeys = ['id'];

    public function all() {
        return $this->select('orders.*', '(SELECT SUM(orderrows.quantity * orderrows.price) FROM orderrows WHERE orderrows.Orders_id = orders.id) AS total');
    }

    public function user () {
        return new HasOne(new User, 'Users_id', 'id', 'user');
    }

    public function row () {
        $orderRow = new OrderRow;
        $orderRow->with('product');

        return new HasMany($orderRow, 'Orders_id', 'id', 'row');
    }

    public function country () {
        return new HasOne(new Country, 'country_id', 'id', 'country');
    }
}