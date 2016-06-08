<?php
namespace App\Guards;

use IPHP\Session\SessionManager;
use App\Cart;
use App\CartItem;
use App\Product;

class CartSessionGuard {
	private $cart;
	private $sessionManager;
	private $sessionKey;
	private $initialized = false;

	public function __construct (SessionManager $sessionManager, string $sessionKey = '', Cart $cart) {
		$this->sessionManager 	= $sessionManager;
		$this->sessionKey 		= $sessionKey;
        $this->cart             = $cart;
		$sessionCart 			= $sessionManager->get($sessionKey);
	}

    public function initialized ():bool {
        return $this->initialized;
    }

    public function loadable ():bool {
        return $this->sessionManager->get($this->sessionKey) != NULL;
    }

    public function load ():bool {
        $cartSession        = $this->sessionManager->get($this->sessionKey);
        if (!is_array($cartSession)) {
            $cartSession = [];
        }

        if (!$this->initialized){
            $productModel = new Product;
            foreach ($cartSession as $productId => $quantity) {
                $product = $productModel->findOrFail((int)$productId);
                $item = new CartItem($product, (int)$quantity);

                $this->cart->addItem($item);
            }

            $this->initialized  = true;
        }

        return true;
    }

    public function save ():bool {
        if ($this->initialized){
            
            $items          = $this->cart->getItems();
            $cartSession    = [];

            foreach ($items as $cartItem) {
                $cartSession[$cartItem->getProduct()->retreive('id')] = $cartItem->getQuantity();
            }
            
            $this->initialized  = false;
          
            $this->sessionManager->set($this->sessionKey, $cartSession);

            return false;
        } else {
            return false;
        }
    }
}