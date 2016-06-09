<?php
namespace App\Controllers\Frontend;

use IPHP\Http\Request;

use IPHP\Validation\Validator;
use IPHP\Validation\Rule;

use App\OrderRow;
use App\Controllers\Controller;
use App\Order;
use App\Country;
use App\Helpers\InputToAssocHelper;
use App\Cart;
use App\Guards\UserGuard;
use App\Guards\CartSessionGuard;

class OrdersController extends Controller
{
    use InputToAssocHelper;
    private $orderKey = 'order';

    public function showCheckout(Cart $cart, UserGuard $user)
    {
        $count = $cart->getCount();
        if($count>0) {

            $order = new Order();
            $countries = $this->getProcessedCountries();
            $user = $user->getUser();
            if ($user) {
                $order->set('firstname', $user->retreive('firstname'));
                $order->set('lastname', $user->retreive('lastname'));
                $order->set('address', $user->retreive('address'));
                $order->set('country_id', $user->retreive('country_id'));
                $order->set('zip', $user->retreive('zip'));
                $order->set('city', $user->retreive('city'));                
                $order->set('email', $user->retreive('email'));
            }

            return $this->view('frontend::checkout::checkout.php', ['order' => $order, 'countries' => $countries]);
        }

        return $this->redirect()->toRoute('FrontendCategories');
    }

    public function postCheckout(Request $request, Validator $validator, Cart $cart, CartSessionGuard $guard,  UserGuard $userGuard)
    {
        $order = new Order;

        if ($this->save($order, $validator, $request, $cart, $userGuard->getUser())) {
            //Save the order under a new key
            $guard->save($this->orderKey);
            //reset the current cart
            $guard->reset($guard->getKey());

        	$this->redirect()->toRoute('CheckoutStatus');
        }

        return $this->view('frontend::checkout::checkout.php', [
            'order' => $order, 
            'countries' => $this->getProcessedCountries(), 
            'errors'    => $validator->getErrors()
        ]);
    }

    public function showStatus (Cart $cart, CartSessionGuard $guard) {
        //Fills the cart object with the saved
        $guard->reload($this->orderKey);

        if ($cart->getCount() > 0) {
            $guard->reset($this->orderKey);

            return $this->view('frontend::checkout::thankyou.php', [
                'shoppingcart' => $cart
            ]);
        } else {
            $this->redirect()->toRoute('FrontendCategories');
        }
    }


    /**
     * [getValidator description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    private function save (Order $order, Validator $validator, Request $request, Cart $cart, $user = null) {
        $validator->addRules([
            new Rule('firstname', 'Voornaam', ['required', 'min:size=2', 'max:size=20']),
            new Rule('lastname', 'Achternaam', ['required', 'min:size=2', 'max:size=40']),
            new Rule('country_id', 'Land', ['required']),
            new Rule('city', 'Woonplaats', ['required', 'min:size=2', 'max:size=70']),
            new Rule('address', 'Adres', ['required', 'min:size=4', 'max:size=120']),
            new Rule('zip', 'Postcode', ['required', 'min:size=4', 'max:size=120']),
            new Rule('email', 'Email', ['required', 'email']),
            new Rule('telephone', 'Telefoon', ['required', 'alpha_num', 'min:size=6', 'max:size=16']),
        ]);

        $all = $request->all();

        if ($validator->validate($all)){
            $order->set('firstname', $all['firstname']->getValue());
            $order->set('lastname', $all['lastname']->getValue());
            $order->set('country_id', $all['country_id']->getValue());
            $order->set('city', $all['city']->getValue());
            $order->set('address', $all['address']->getValue());
            $order->set('zip', $all['zip']->getValue());
            $order->set('email', $all['email']->getValue());
            $order->set('telephone', $all['telephone']->getValue());
            if ($user) {
                $order->set('Users_id', $user->retreive('id'));
            }
            //save user
            $order->set('status', 'placed');
            //$order->deliver_date = date('Y-m-d H:i:s');

            if ($order->save()){
                $items = $cart->getItems();
                $orderId = $order->retreive('id');
                foreach($items as $item) {
                    $product = $item->getProduct()->contents();
                    $row = new OrderRow();
                    $row->set('Orders_id', $orderId);
                    $row->set('Products_id', $product->id);
                    $row->set('quantity', $item->getQuantity());
                    $row->set('price', $product->price);
                    $row->set('vat', $product->vat->rate);

                    if(!$row->save()) {
                        $validator->errors()->add('main', 'Er is een onbekende fout opgetreden tijdens het opslaan!');
                        return false;
                    }
                }

                return true;
            }
        }

        return false;
    }
    /**
     * [getProcessedVat description]
     * @return [type] [description]
     */
    private function getProcessedCountries() {
        $country = new Country;
        return $this->collectionToAssoc($country->getCollection($country->select()->orderBy('name')), 'id', 'name');
    }
}