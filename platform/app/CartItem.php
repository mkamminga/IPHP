<?php

namespace App;

class CartItem {
    protected $product;
    protected $quantity = 1; 

    public function __construct (Product $product, int $quantity) {
        $this->product = $product;

        if ($quantity > 0) {
            $this->quantity = $quantity;
        }
    }

    public function updateQuantity (int $num) {
        if ($num > 0) {
            $this->quantity = $num;
        }
    }

    public function getQuantity ():int {
        return $this->quantity;
    }

    public function getProduct (): Product {
        return $this->product;
    }

    public function total ():int  {
        return $this->quantity * $this->product->retreive('price');
    }
}
