<?php

namespace App\Controllers\Backend;

use IPHP\Http\Request;

use App\Http\Requests;
use App\Controllers\Controller;
use App\Navigation;
use IPHP\Validation\Validator;
use IPHP\Validation\Rule;

class NavigationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function overview()
    {
        $navigation = new Navigation;

        return $this->view('cms::navigations::overview.php', ['navigations' => $navigation->get( $navigation->allSorted() )]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function showAdd()
    {
        return $this->view('cms::navigations::create.php');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function post(Request $request, Validator $validator)
    {
        $navigation = new Navigation;
        if (!$this->save($validator, $request, $navigation)){
            return $this->showAdd()->setVar('errors', $validator->getErrors());
        } else {
            $this->redirect()->toRoute('NavigationOverview');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showEdit($id)
    {
        $navigation = $this->getNavigationOrFail($id);

        return $this->view('cms::navigations::edit.php', ['navigation' => $navigation]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function put(Request $request, Validator $validator, int $id)
    {
        $navigation = $this->getNavigationOrFail($id);
        if (!$this->save($validator, $request, $navigation)){
            return $this->view('cms::navigations::edit.php', ['navigation' => $navigation])->setVar('errors', $validator->getErrors());
        } else {
            $this->redirect()->toRoute('NavigationOverview');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function showDelete(int $id)
    {
        $navigation = $this->getNavigationOrFail($id);

        return $this->view('cms::default.delete.php', ['name' => $navigation->retreive('name')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function delete($id)
    {
        $navigation = $this->getNavigationOrFail($id);
        $navigation->delete();

        $this->redirect()->toRoute('NavigationOverview');
    }

    private function getNavigationOrFail (int $id) :Navigation {
        $navigation = new Navigation;
        $navigation = $navigation->find($id);

        if (!$navigation) {
            throw new \Exception('Item with id "'. $id .'" was not found!');
        }

        return $navigation;
    }

    private function save (Validator $validator, Request $request, Navigation $navigation) {
        $validator->addRules([
            new Rule('name', 'Naam', ['required', 'min:size=2', 'max:size=30']),
            new Rule('link', 'Link', ['required', 'min:size=2', 'max:size=30']),
            new Rule('position', 'Positie', ['required', 'num'])
        ]);

        $requestData = $request->all();

        if ($validator->validate($requestData)) {

            $navigation->set('name', $requestData['name']->getValue());
            $navigation->set('link', $requestData['link']->getValue());
            $navigation->set('position', $requestData['position']->getValue());

            if (!$navigation->save()){
                $validator->addError('main', 'Er is een onbekende fout opgetreden tijdens het opslaan!');
                
                return false;
            }

            return true;

        }

        return false;

    }
}
