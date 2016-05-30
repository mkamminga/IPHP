<?php

namespace App\Controllers\Backend;

use IPHP\Http\Request;
use IPHP\Validation\Validator;
use IPHP\Validation\Rule;

use IPHP\File\File;
use IPHP\File\UploadedFile;

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
    public function post(Request $request, Validator $validator)
    {
        $category = new Category;
        if ($this->save($category, $request, $validator)) {
            if ($this->saveImage($category, $request)) {
                $this->redirect()->toRoute('CategoriesOverview');
            } else {
                //onfailure to create a dir in which to save the newly created category, reverse the creation
                $file = new File;
                $file->removeDir($this->getSavePath($category));
                $category->delete();

                $validator->addError('main', 'Kon afbeelding niet opslaan!');
            }
        } 

        return $this->showAdd()->setVar('errors', $validator->getErrors());
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showEdit($id)
    {
        $category = $this->getCategoryOrFail($id);

        $parents = $this->parents();
        if (isset($parents[$id])) {
            unset($parents[$id]);
        }
        return $this->view('cms::categories::edit.php', ['category' => $category, 'parents' => $parents]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function put($id, Request $request, Validator $validator)
    {
        $category = $this->getCategoryOrFail($id);
        $oldFile = $category->retreive('thumb');

        if ($this->save($category, $request, $validator, true)) {
            if ($oldFile != $category->retreive('thumb')) {
                if ($this->saveImage($category, $request)){
                    $file = new File;
                    $file->remove($this->getSavePath($category) . DIRECTORY_SEPARATOR . $oldFile);

                    $this->redirect()->toRoute('CategoriesOverview');
                } else {
                    $category->set('thumb', $oldFile);
                }
            } else {
                $this->redirect()->toRoute('CategoriesOverview');
            }
        } 
        //something must have gone wrong, show the edit view again with the errors
        $parents = $this->parents();
        if (isset($parents[$id])) {
            unset($parents[$id]);
        }

        return $this->view('cms::categories::edit.php', ['category' => $category, 'parents' => $parents, 'errors' => $validator->getErrors()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function showDelete($id)
    {
        $category = $this->getCategoryOrFail($id);
        return $this->view('cms::default.delete.php', ['name' => $category->retreive('name')]);
    }

    public function delete (int $id) {
        $category = $this->getCategoryOrFail($id);

        $file = new File;
        $path = $this->getSavePath($category);
        if ($file->exists($path)){
            $file->removeDir($path);
        }

        if ($category->softDelete()){
            $this->redirect()->toRoute('CategoriesOverview');
        } else {
            throw new Exception("Kon categorie niet verwijderen");
        }
    }

    private function getCategoryOrFail (int $id) {
        $category = new Category;
        $category = $category->find($id);

        if (!$category) {
            throw new \Exception("Couldn't find category");
        }

        return $category;
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

    private function save (Category $category, Request $request, Validator $validator, $update = false) {
        $fileRequirements = ['mime:prefix=image|types=jpg,jpeg,png,gif'];

        if (!$update) {
            array_unshift($fileRequirements, 'required');
        }

        $validator->addRules([
            new Rule('name', 'Naam', ['required', 'min:size=2', 'max:size=20']),
            new Rule('image', 'Afbeelding', $fileRequirements)
        ]);
        $requestData = $request->all();

        if ($validator->validate($requestData)) {
            $category->set('name', $requestData['name']->getValue());
            if (isset($requestData['Parent_id'])){
                $category->set('Parent_id', $requestData['Parent_id']->getValue());
            }
            if (!$requestData['image']->isNull()){
                $file = new File;
                $category->set('thumb', $file->normilizeName($requestData['image']->realName()));
            }
            //on failure to save return false
            if (!$category->save()){
                $validator->errors()->add('main', 'Er is een onbekende fout opgetreden tijdens het opslaan!');
                return false;
            } 
           
            return true;
        }

        return false;
    }

    private function saveImage (Category $category, Request $request) {
        $file = new File;
        $uploadedFile = new UploadedFile($request->fromFiles('image')->getValue());
        $dir = $this->getSavePath($category);
        //throws exception onfailure
        try {
            $file->createDir($dir);
            if (!$uploadedFile->move($dir, $category->retreive('thumb'))){
                return false;
            }
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    private function getSavePath (Category $category) {
        return public_path . DIRECTORY_SEPARATOR . categories_images_dir . DIRECTORY_SEPARATOR . $category->retreive('id');
    }
}