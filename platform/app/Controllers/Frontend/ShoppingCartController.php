<?php
/**
 * @author Alhric Lacle <alhriclacle@gmail.com>
 * @project Web3
 * @created 24-Aug-15 5:22 PM
 */


namespace App\Controllers\Frontend;

use IPHP\Http\Request;

use App\Guards\CartSessionGuard;
use App\Controllers\Controller;
use App\Product;
use App\Cart;
use App\CartItem;


class ShoppingCartController extends Controller
{
    /**
     * Display a listing of cart items.
     *
     * @return Response
     */
    public function showCart (Cart $cart) {
        return $this->view('frontend::shoppingcart.php', ['shoppingcart' => $cart]);
    }
    /**
     * Add a product to the cart. If the product already is present in the cart then update the quantity.
     *
     * @return Response
     */
    public function addProductToCart (Request $request, Cart $cart, CartSessionGuard $guard)
    {
        $product    = (new Product)->findOrFail($request->get('id')->getValue());
        $quantity   = (int)$request->get('quantity')->getValue();
        $item       = $cart->getItemByProduct($product);
        //if a cartitem linked to the same product exists, then update the quantity
        if ($item){
            $item->updateQuantity($item->getQuantity() + $quantity);
        } else {
            //new item
            $cart->addItem(new CartItem($product, $quantity));
        }
        //save to the session
        $guard->save($guard->getKey());
    
        return $this->json([
            'status' => 'success',
            'data' => [
                'count' => $cart->getCount()
            ]
        ]);
    }

    /**
     * Remove an item from the cart (if it exists in the cart).
     *
     * @return Response
     */
    public function removeItem(Request $request, Cart $cart, CartSessionGuard $guard)
    {
        $product = (new Product)->findOrFail((int)$request->get('id')->getValue());

        $cartItem = $cart->getItemByProduct($product);
        if (!$cartItem){
            return $this->json([
                'status' => 'error',
                'errors' => [
                    'message' => "Geen producten in winkel gevonden met dat nummer."
                ]
            ]);
        } else {

            $cart->removeItem($cartItem);
            $guard->save();

            return $this->json([
                'status' => 'success',
                'data' => [
                    'count' => $cart->getCount()
                ]
            ]);
        }
    }
    /**
     * Update the quantity of an cartitem (if exists). 
     *
     * @return Response
     */
    public function updateItemQuantity (Request $request, Cart $cart, CartSessionGuard $guard) 
    {
        $product = (new Product)->findOrFail((int)$request->get('id')->getValue());

        $cartItem = $cart->getItemByProduct($product);
        if (!$cartItem){
            return $this->json([
                'status' => 'error',
                'errors' => [
                    'message' => "Geen producten in winkel gevonden met dat nummer."
                ]
            ]);
        } else {
            $cartItem->updateQuantity((int)$request->get('quantity')->getValue());
            $guard->save($guard->getKey());
        }

        return $this->json([
            'status' => 'success',
            'data' => [
                'count' => $cart->getCount()
            ]
        ]);
    }
    /**
     * Return the shoppingCart
     *
     * @return Response
     */
    public function shoppingCart (Cart $cart) {
        return $this->view('partials::ajaxshoppingcart.php', [
            'shoppingcart' => $cart
        ]);
    }
}