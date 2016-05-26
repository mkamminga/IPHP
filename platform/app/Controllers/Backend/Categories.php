<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Categorie;
use Validator;

class Categories extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $categories = Categorie::all();

        return view('cms.categories.overview', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('cms.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        $validator = $this->validator($requestData);


        if (!$validator->fails()) {
            $categorie = new Categorie();
            $categorie->name = $requestData['name'];

            if ($categorie->save()){
                return redirect()->route('beheer.categories.index');
            } else {
                $validator->errors()->add('main', 'Er is een onbekende fout opgetreden tijdens het opslaan!');
            }

        }

        return redirect()->route('beheer.categories.create')
                ->withErrors($validator)
                ->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $category = Categorie::find($id);

        return view('cms.categories.delete', ['category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $category = Categorie::find($id);

        if (!$category){
            throw new Exception("Category not foundx!");
        }


        return view('cms.categories.edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        $requestData = $request->all();
        $validator = $this->validator($requestData);


        if (!$validator->fails()) {
            $categorie = Categorie::find($id);
            if (!$categorie) {
                throw new Exception("Category not found!");
            } 

            $categorie->name = $requestData['name'];

            if ($categorie->save()){
                return redirect()->route('beheer.categories.index');
            } else {
                $validator->errors()->add('main', 'Er is een onbekende fout opgetreden tijdens het opslaan!');
            }

        }

        return redirect()->route('beheer.categories.edit', ['id' => $id])
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
        if (($category = Categorie::find($id))) {
            $category->delete();
        }

        return redirect()->route('beheer.categories.index');
    }

    private function validator ($requestData) {
        return Validator::make($requestData, [
            'name' => 'required|max:20',
        ]);
    }
}
