<?php

namespace App\Controllers\Backend;

use IPHP\Http\Request;
use IPHP\Translation\Translator;

use IPHP\Validation\Validator;
use IPHP\Validation\Rule;

use App\Controllers\Controller;
use App\Order;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function overview()
    {
        $ordersModel = new Order;
        $orders = $ordersModel->with('user')->get($ordersModel->all()->orderBy('created_at'));

        return $this->view('cms::orders::overview.php', [
            'orders' => $orders
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showEdit($id)
    {
        $orderModel = new Order;
        $order = $orderModel->with('row')
                            ->with('user')
                            ->with('country')
                            ->find($id);

        return $this->view('cms::orders::edit.php', [
            'order' => $order,
            'orderStates' => $this->getProcessedOrderStates(),

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request, Validator $validator)
    {
        $order = $orderModel->with('row')
                            ->with('user')
                            ->with('country')
                            ->findOrFail($id);

        $requestData = $request->all();

        $validator->addRules([
            new Rule('city', 'Woonplaats', ['required', 'max:size=20']),
            new Rule('address', 'Adres', ['required', 'min:size=3', 'max:size=60']),
            new Rule('zip', 'Postcode', ['required', 'max:size=10']),
            new Rule('status', 'Status', ['required']),
        ]);

        if ($validator->validate($requestData)) {
            
            $order->set('city', $requestData['city']->getValue());
            $order->set('address', $requestData['adres']->getValue());
            $order->set('zip', $requestData['zip']->getValue());
            $order->set('status', $requestData['status']->getValue());

            if ($order->save()){
                return $this->redirect()->toRoute('OrdersOverview');
            } else {
                $validator->errors()->add('main', 'Er is een onbekende fout opgetreden tijdens het opslaan!');
            }
        }

        return $this->view('cms::orders::edit.php', [
            'order' => $order,
            'orderStates' => $this->getProcessedOrderStates(),
            'errors' => $validator->getErrors()
        ]);
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
    /**
     *
     */
    private function getProcessedOrderStates () {
        $states = new \App\OrderState;
        $data = $states->getCollection($states->select()->orderBy('name'));
        $translated = [];
        $translator = $this->sm->getService('translator');
        foreach ($data as $state) {
            $translated[$state->name] = $translator->get('orderstates', $state->name);
        }

        return $translated;
    }
}