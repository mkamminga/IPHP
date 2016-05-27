<?php

namespace App\Controllers\Backend;

use IPHP\Http\Request;
use IPHP\Validation\Validator;
use IPHP\Validation\Rule;

use IPHP\File\File;

use App\Controllers\Controller;
use App\Category;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function overview()
    {
        $category = new Category;
        $categories = $category->get($category->byName( $category->allWithParent() ) );

        return $this->view('cms::categories::overview.php', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function showAdd()
    {
        return $this->view('cms::categories::create.php', ['parents' => $this->parents()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function post(Request $request, Validator $validator, File $file)
    {
        $category = new Category;
        if (!$this->save($category, $request, $validator, $file)) {
            return $this->showAdd()->setVar('errors', $validator->getErrors());
        } else {
            return $this->redirect()->toRoute('CategoriesOverview');
        }
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
    public function showEdit($id)
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
    public function put($id, Request $request)
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

    private function parents () {
        $category = new Category;

        $categories = $category->get($category->byName($category->allParent()));

        $parents = [];
        foreach ($categories as $parent) {
            $parents[$parent->retreive('id')] = $parent->retreive('name');
        }

        return $parents;
    }

    private function save (Category $category, Request $request, Validator $validator, File $file, $update = false) {
        $validator->addRules([
            new Rule('name', 'Naam', ['required', 'min:size=2', 'max:size=20']),
            new Rule('image', 'Afbeelding', ['required', 'mime:prefix=image|types=jpg,jpeg,png,gif'])
        ]);

        if ($validator->validate($request->all())) {
            exit;
            $categorie->name = $requestData['name'];

            if (!$categorie->save()){
                $validator->errors()->add('main', 'Er is een onbekende fout opgetreden tijdens het opslaan!');
                return false;
            }

            return true;
        }

        return false;
    }
}
