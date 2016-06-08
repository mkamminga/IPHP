<?php
namespace App\Filters;

use IPHP\App\ServiceManager;
use IPHP\Events\EventManager;
use IPHP\Http\Routing\IRouteFilterable;

use App\Guards\CartSessionGuard;
use App\Cart;

class CartFilter implements IRouteFilterable {
	private $eventManager;

	public function __construct (EventManager $eventManager) {
		$this->eventManager = $eventManager;
	}

	public function handle () {
        
        $key = 'cart';
		$this->eventManager->register('route.pre.dispatch', function (ServiceManager $sm) use ($key) {
           
            $cart       = new Cart;
            $cartGuard  = new CartSessionGuard($sm->getService('sessionManager'), $key, $cart);

            $cartGuard->load();
            
			$sm->registerInstanceAlias('cart', Cart::class, $cart);
            $sm->registerInstanceAlias('cartSessionGuard', CartSessionGuard::class, $cartGuard);
		});
	} 
}