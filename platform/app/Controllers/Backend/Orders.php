<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Order;
use App\Http\Models\Orderrow;
use App\Http\Traits\ReturnAssoc;
use Validator;
use Config;

class Orders extends Controller
{
    use ReturnAssoc;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $input = $request->all();

        
        $orders = Order::withTotal()->with('user.group');
        if (isset($input['order_nr']) && !empty($input['order_nr'])) {
            $orders->withOrdernr($input['order_nr']);
        }

        $orders = $orders->orderBy('created_at')
                        ->paginate(10);
        $orderStates = $this->getProcessedOrderStates();

        return view('cms.orders.overview', ['orders' => $orders, 'orderStates' => $orderStates])->withInput($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        return view('cms.orders.delete', ['order' => $order]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $order = Order::withTotal()->with(['rows.product','user.group'])->find($id);
        $orderStates = $this->getProcessedOrderStates();
        $vat = Orderrow::vatTotals($id)->get();
        $countries = $this->getProcessedCountries();

        return view('cms.orders.edit', [
            'countries' => $countries,
            'order' => $order,
            'vat' => $vat, 
            'orderStates' => $orderStates
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $order = Order::find($id);
        $requestData = $request->all();
        $validator = $this->getValidator($requestData);

        if (!$validator->fails()) {
            
            $order->country = $requestData['country'];
            $order->city = $requestData['city'];
            $order->adres = $requestData['adres'];
            $order->zip = $requestData['zip'];
            $deliver_date = date_create_from_format('d/m/Y', $requestData['formatted_deliver_date']);
            $order->deliver_date = $deliver_date->format('Y-m-d');
            $order->status = $requestData['status'];

            if ($order->save()){
                return redirect()->route('beheer.orders.index');
            } else {
                $validator->errors()->add('main', 'Er is een onbekende fout opgetreden tijdens het opslaan!');
            }
        }

        return redirect()->route('beheer.orders.edit', [$id])
            ->withErrors($validator)
            ->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if (($order = Order::find($id))) {
            $order->delete();
        }

        return redirect()->route('beheer.orders.index');
    }

    private function getValidator (array $data) {
        return Validator::make($data, [
            'country' => 'required|max:25',
            'city' => 'required|max:20',
            'adres' => 'required|min:3|max:60',
            'zip' => 'required|max:10',
            'formatted_deliver_date' => 'required|date_format:d/m/Y',
            'status' => 'required',
        ]);
    }
    /**
     * [getProcessedVat description]
     * @return [type] [description]
     */
    private function getProcessedOrderStates() {
        $order_status = Config::get('static_values.order_status');

        return $this->getAssocValues($order_status);
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