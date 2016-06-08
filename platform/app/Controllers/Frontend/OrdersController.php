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

class OrdersController extends Controller
{
    use InputToAssocHelper;
    public function showCheckout(Cart $cart, UserGuard $user)
    {
        $count = $cart->getCount();
        if($count>0) {

            $order = new Order();
            $countries = $this->getProcessedCountries();
            $user = $user->getUser();
            if ($user) {
                $order->set('firstname', $user->retreive('name'));
                $order->set('lastname', $user->retreive('lastname'));
                $order->set('adres', $user->retreive('address'));
                //$order->country = $user->country;
                $order->set('zip', $user->retreive('zip'));
                $order->set('city', $user->retreive('city'));                
                $order->set('email', $user->retreive('email'));
            }

            return $this->view('frontend::checkout::checkout.php', ['order' => $order, 'countries' => $countries]);
        }

        return $this->redirect()->toRoute('CategoriesOverview');
    }

    public function postCheckout(Request $request, Validator $validator)
    {
        $order = new Order;

        if ($this->save($order, $validator, $request)) {
        	$this->redirect()->toRoute('frontend::thankyou.php');
        }

        return $this->view('frontend::checkout::checkout.php', [
            'order' => $order, 
            'countries' => $this->getProcessedCountries(), 
            'errors'    => $validator->getErrors()
        ]);
    }


    /**
     * [getValidator description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    private function save (Order $order, Validator $validator, Request $request) {
        $validator->addRules([
            new Rule('firstname', 'Voornaam', ['required', 'min:size=2', 'max:size=20']),
            new Rule('lastname', 'Achternaam', ['required', 'min:size=2', 'max:size=40']),
            new Rule('country_id', 'Land', ['required']),
            new Rule('city', 'Woonplaats', ['required', 'min:size=2', 'max:size=70']),
            new Rule('adres', 'Adres', ['required', 'min:size=4', 'max:size=120']),
            new Rule('zip', 'Postcode', ['required', 'min:size=4', 'max:size=120']),
            new Rule('email', 'Email', ['required', 'email']),
            new Rule('telephone', 'Telefoon', ['required', 'alpha_num', 'min:6', 'max:16']),
        ]);

        $all = $request->all();

        if ($validator->validate($all)){
            $order->set('firstname', $user->retreive('name'));
            $order->set('lastname', $user->retreive('lastname'));
            $order->set('adres', $user->retreive('address'));
            //$order->country = $user->country;
            $order->set('zip', $user->retreive('zip'));
            $order->set('city', $user->retreive('city'));                
            $order->set('email', $user->retreive('email'));

            $user = $request->user();
            if ($user){
                $order->Users_id = $user->id;
            }
            $order->telephone = $requestData['telephone'];
            $order->status = 'placed';
            //$order->deliver_date = date('Y-m-d H:i:s');

            if ($order->save()){
                $cart = $request->session()->get('cart')->getItems();
                foreach($cart as $item) {
                    $row = new Orderrow();
                    $row->Orders_id = $order->id;
                    $row->Products_id = $item['id'];
                    $row->quantity = $item['quantity'];
                    $row->price = $item['price'];
                    $row->vat = $item['vat'];

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