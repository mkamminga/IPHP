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

    public function loadable (string $key):bool {
        return $this->sessionManager->get($key) != NULL;
    }

    public function load (string $key):bool {
        $cartSession        = $this->sessionManager->get($key);
        if (!is_array($cartSession)) {
            $cartSession = [];
        }

        if (!$this->initialized){
            $productModel = new Product;
            $productModel->with('vat');

            foreach ($cartSession as $productId => $quantity) {
                $product = $productModel->findOrFail((int)$productId);
                $item = new CartItem($product, (int)$quantity);

                $this->cart->addItem($item);
            }

            $this->initialized  = true;
        }

        return true;
    }

    public function reload (string $key): bool {
        $this->initialized = false;

        return $this->load($key);
    } 

    public function save (string $key):bool {
        if ($this->initialized){
            
            $items          = $this->cart->getItems();
            $cartSession    = [];

            foreach ($items as $cartItem) {
                $cartSession[$cartItem->getProduct()->retreive('id')] = $cartItem->getQuantity();
            }
            
            $this->initialized  = false;
          
            $this->sessionManager->set($key, $cartSession);

            return true;
        } else {
            return false;
        }
    }

    public function reset (string $key) {
        $this->sessionManager->set($key, []);
    }

    public function getKey () {
        return $this->sessionKey;
    }
}