<?php
/**
 * @author Alhric Lacle <alhriclacle@gmail.com>
 * @project Web3
 * @created 24-Aug-15 4:52 PM
 */


namespace App\Http\Controllers\Frontend;

use App\Http\Models\Orderrow;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Order;
use Illuminate\Http\Request;
use App\Http\Traits\ReturnAssoc;
use Validator;
use Mail;
use Config;

class Orders extends Controller
{
    use ReturnAssoc;
    public function checkOut(Request $request)
    {
        $count = $request->session()->get('cart')->getCount();
        $user = $request->user();
        if($count>0) {

            $order = new Order();
            $countries = $this->getProcessedCountries();
            if ($user) {
                $order->firstname = $user->name;
                $order->lastname = $user->lastname;
                $order->adres = $user->address;
                $order->country = $user->country;
                $order->zip = $user->zip;
                $order->city = $user->city;
                $order->email = $user->email;
            }

            return view('customerPages.checkout', ['order' => $order, 'countries' => $countries]);
        }
        return redirect()->route('categories');
    }

    public function sendMail()
    {
        return redirect()->route('thankyou');
    }

    public function submit(Request $request)
    {

        $requestData = $request->all();
        $validator = $this->getOrderValidator($requestData);

        if (!$validator->fails()) {

            $order = new Order();

            $user = $request->user();
            if ($user){
                $order->Users_id = $user->id;
            }

            $order->firstname = $requestData['firstname'];
            $order->lastname = $requestData['lastname'];
            $order->adres = $requestData['adres'];
            $order->city = $requestData['city'];
            $order->zip = $requestData['zip'];
            $order->telephone = $requestData['telephone'];
            $order->email = $requestData['email'];
            $order->status = 'placed';
            //$order->deliver_date = date('Y-m-d H:i:s');

            if ($order->save()){
                $cart = $request->session()->get('cart')->getItems();
                foreach($cart as $item)
                {
                    $row = new Orderrow();
                    $row->Orders_id = $order->id;
                    $row->Products_id = $item['id'];
                    $row->quantity = $item['quantity'];
                    $row->price = $item['price'];
                    $row->vat = $item['vat'];

                    if(!$row->save()) {
                        $validator->errors()->add('main', 'Er is een onbekende fout opgetreden tijdens het opslaan!');
                        return redirect()->route('checkoutPage')
                        ->withErrors($validator)
                        ->withInput();
                    }
                }

                $email = $order->email;
                $total = $request->session()->get('cart')->getTotal();
                if($email) {
                    Mail::send('mail.confirmation',
                        ['email' => $email,'order'=>$order,'cart'=>$cart,'total'=>$total], function ($m) use ($email) {
                        $m->to($email)->subject('Confirmation');
                    });
                }

                $request->session()->forget('cart');


                return redirect()->route('thankyou');
            } else {
                $validator->errors()->add('main', 'Er is een onbekende fout opgetreden tijdens het opslaan!');
            }
        }

        return redirect()->route('checkoutPage')
        ->withErrors($validator)
        ->withInput();
    }


    /**
     * [getValidator description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    private function getOrderValidator (array $requestData) {
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'country' => 'required',
            'adres' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'email' => 'required',
        ];

        return Validator::make($requestData, $rules);
    }
     /**
     * [getProcessedVat description]
     * @return [type] [description]
     */
    private function getProcessedCountries() {
        $countries = Config::get('static_values.countries');

        return $this->getAssocValues($countries);
    }
}