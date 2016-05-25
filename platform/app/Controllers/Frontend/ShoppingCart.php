<?php
/**
 * @author Alhric Lacle <alhriclacle@gmail.com>
 * @project Web3
 * @created 24-Aug-15 5:22 PM
 */


namespace App\Http\Controllers\Frontend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Product;
use App\Http\Models\Cart;
use Illuminate\Http\Request;

class ShoppingCart extends Controller
{

    public function addToShoppingcart(Request $request, $id)
    {
        $cart = $this->getCart($request);
        $product = Product::findOrFail($id);
        if ($product){
            $cart->addItem($product);
        }

        $pCount = $cart->getCount();

        return response()->json(['pCount'=>$pCount]);

    }

    public function removeItem(Request $request,$id)
    {
        $product = Product::findOrFail($id);
        $cart = $this->getCart($request);

        if ($product) {
            $cart->removeItem($product);
        }

        $pCount = $cart->getCount();

        return response()->json(['pCount'=>$pCount]);
    }

    public function updateItemQuantity (Request $request, $id, $quantity) 
    {
        $product = Product::findOrFail($id);
        $cart = $this->getCart($request);

        if ($product) {
            $cart->updateQuantity($product, $quantity);
        }

        $pCount = $cart->getCount();

        return response()->json(['pCount'=>$pCount]);
    }

    public function shoppingcart(Request $request)
    {
        return view('customerPages.shoppingcart', $this->getShoppingcartData($request));
    }

    public function shoppingCartOnly (Request $request) 
    {
        return view('partials.shoppingcart', $this->getShoppingcartData($request));
    }

    private function getShoppingcartData(Request $request)
    {
        $cart = $this->getCart($request);
        $state = 'Shoppingcart';
        $shoppingcart = $cart->getItems();


        if($shoppingcart == null){
            $state = 'Shoppingcart is empty';
        }


        return [
            'shoppingcart' => $shoppingcart,
            'totalprice' => $cart->getTotal(),
            'state' => $state
        ];
    }

    private function getCart (Request $request) {
        if(!$request->session()->has('cart')) {
            $request->session()->put('cart', new Cart());
        }

        return $request->session()->get('cart');
    }

}