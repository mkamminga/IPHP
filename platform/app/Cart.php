<?php

namespace App;

class Cart {
    protected $cartItems = array();

    public function reset () {
        $this->cartItems = [];
    }

    public function addItem (CartItem $cartItem) {
        $this->cartItems[] = $cartItem;
    }

    public function removeItem (CartItem $cartItem):bool {
        $index = array_search($cartItem, $this->cartItems);

        if (is_int($index) && $index >= 0) {
            unset($this->cartItems[$index]);

            return true;
        }

        return false;
    }

    public function getItemByProduct (Product $product) {
        foreach ($this->cartItems as $item) {
            if ($item->getProduct()->retreive('id') == $product->retreive('id')) {
                return $item;
            }
        }

        return NULL;
    }

    public function getItems () {
        return $this->cartItems;
    }

    public function getCount () {
        $count = 0;

        foreach ($this->cartItems as $item) {
            $count+= $item->getQuantity();
        }

        return $count;
    }

    public function getTotal () {
        $total = 0;

        foreach ($this->cartItems as $item) {
            $total+= $item->total();
        }

        return $total;
    }
}
