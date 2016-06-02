<?php

namespace App\Controllers\Backend;
use App\Controllers\Controller;

use App\Product;
use App\Category;
use App\VatRate;

use IPHP\Http\Request;
use IPHP\Validation\Validator;
use IPHP\Validation\Rule;
use App\Helpers\InputToAssocHelper;

use IPHP\File\File;
use IPHP\File\UploadedFile;

class ProductsController extends Controller
{
    use InputToAssocHelper;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function overview(Request $request)
    {

        $inputs         = $request->all();
        $products       = new Product;
        $products       = $products->with('category');
        $productData    = $products->all();
        if (isset($inputs['artikelnr']) && !empty($inputs['artikelnr']->getValue())) {
            $productData = $products->withArtnr($productData, (int)$inputs['artikelnr']->getValue());
        }
       
        $productData = $products->get($productData->orderBy('name'));

        return $this->view('cms::products::overview.php', ['products' => $productData]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function showAdd()
    {
        $vatSettings    = $this->getVat();
        $categories     = $this->getCategories();
        
        return $this->view('cms::products::create.php', [
            'categories' => $categories, 
            'vatRates' => $vatSettings,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function post(Request $request, Validator $validator)
    {
        $product = new Product;
        if ($this->save($request, $validator, $product)) {
            if ($this->saveImages($request, $product)) {
                $this->redirect()->toRoute('ProductsOverview');
            } else {
                //reverse any storage of files and the creation of the dir
                $file = new File;
                $file->removeDir($this->getSavePath($product), true);
                $product->delete();
                $validator->addError('main', 'Fout tijdens het opslaan van de afbeeldingen!');
            }
        }

        $viewResponse = $this->showAdd();
        $viewResponse->setVar('errors', $validator->getErrors());
        
        if ($request->get('mainCategory') && !$request->get('mainCategory')->isEmpty()) {
            $subCategories = $this->getSubCategories($request->get('mainCategory')->getValue());
            
            $viewResponse->setVar('subCategories', $this->collectionToAssoc($subCategories, 'id', 'name'));
        }
        
        return $viewResponse;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showEdit(int $id)
    {
        $product        = $this->getProductOrFail($id); 
        $category       = $product->getRelated('category');
        
        $vatSettings    = $this->getVat();
        $categories     = $this->getCategories();
        $subCategories  = $this->collectionToAssoc($this->getSubCategories((int)$category->retreive('Parent_id')), 'id', 'name');
        
        $product->set('mainCategory', $category->retreive('Parent_id'));
        
        return $this->view('cms::products::edit.php', [
            'product'   => $product,
            'categories' => $categories, 
            'subCategories' => $subCategories,
            'vatRates' => $vatSettings,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function put($id, Request $request)
    {
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if (($product = Product::find($id))) {
            $product->delete();
        }
        return redirect()->route('beheer.products.index');
    }
    /**
     * Retreives all sub categories from a provided parent category (id). Returns the result as a jsonresponse
     * 
     * @param int $id parent id
     * @return JsonResponse
     */
    public function ajaxSubcategories ($id) {
        return $this->json($this->getSubCategories((int)$id));
    }
    /**
     * Helper function that fetches and returns a collection of subcategories from a provided parent category id and 
     * 
     * @param int $id parent id
     * @return array collection
     */
    private function getSubCategories (int $parentId) {
        $category = new Category;
        return $category->getCollection($category->byName($category->allFromParent($parentId))); 
    }
    /**
     * [getProcessedVat description]
     * @return [type] [description]
     */
    private function getVat() {
        $vatRates = new VatRate;
        $rates    = $vatRates->get($vatRates->select());
        $return = [];
        foreach ($rates as $rate) {
            $return[$rate->retreive('id')] = $rate->retreive('rate');
        }
        
        return $return;
    }
    
    private function getCategories () {
        $category = new Category;

        $categories = $category->get($category->byName($category->allParent()));

        $parents = [];
        foreach ($categories as $parent) {
            $parents[$parent->retreive('id')] = $parent->retreive('name');
        }

        return $parents;
    }
    /**
     * Attempt to get a product by id. Failure to do so will cause an exception.
     *
     * @param int $id product id
     * return Product
     */
    private function getProductOrFail (int $id) {
        $product = new Product;
        
        $product = $product->with('category')->find($id);
        
        if (!$product){
            throw new Exception("Could not find Product with id: ". $id);
        }
        
        return $product;
    }
    /**
     * [getValidator description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    private function save (Request $request, Validator $validator, Product $product) {
    	
        $validator->addRules([
            new Rule('mainCategory', 'Hoofd categorie', ['required']),
            new Rule('Categories_id', 'Sub categorie', ['required']),
            new Rule('name', 'Naam', ['required', 'min:size=2', 'max:size=20']),
            new Rule('artikelnr', 'Artikel nummer', ['required', 'num']),
            new Rule('price', 'Prijs', ['required', 'regex:expression=/^\d*(\.\d{2})?$/'], ['regex' => ':field is geen geldige prijs!']),
            new Rule('vat_rate_id', 'Btw', ['required']),
            new Rule('short_description', 'Korte omschrijving', ['required', 'min:size=10', 'max:size=255']),
            new Rule('detail', 'Omschrijving', ['required', 'min:size=10', 'max:size=3000']),
            new Rule('main_image_link', 'Hoofd afbeelding', ['required', 'mime:prefix=image|types=jpg,jpeg,png,gif']),
            new Rule('small_image_link', 'Kleine afbeelding', ['required', 'mime:prefix=image|types=jpg,jpeg,png,gif']), 
        ]);
        
        $all = $request->all();
        
        if ($validator->validate($all)){
            //save
            $product->set('Categories_id', $all["Categories_id"]->getValue());
            $product->set('name', $all["name"]->getValue());
            $product->set('artikelnr', $all["artikelnr"]->getValue());
            $product->set('price', $all["price"]->getValue());
            $product->set('vat_rate_id', $all["vat_rate_id"]->getValue());
            $product->set('short_description', $all["short_description"]->getValue());
            $product->set('detail', $all["detail"]->getValue());
            
            $file = new File;
            //Main image
            if (!$all['main_image_link']->isNull()){
                $product->set('main_image_link', $file->normilizeName($all['main_image_link']->realName()));
            }
            // thumb             
            if (!$all['small_image_link']->isNull()){
                $product->set('small_image_link', 'thumb_' . $file->normilizeName($all['small_image_link']->realName()));
            }
            
            if ($product->save()) {
                return true;
            } else {
                $validator->addError('main', 'Er is een onbekende fout opgetreden tijdens het opslaan!');
                
                return false;
            }
        }
    	
        return false;
    }
    
    private function saveImages (Request $request, Product $product) {
        $images = ['main_image_link', 'small_image_link'];
        $dir = $this->getSavePath($product);
        
        try {
            $file = new File;
            //throws exception onfailure
            $file->createDir($dir);
       
            foreach ($images as $image){
                //the image
                if (!$request->fromFiles($image)->isNull()){
                    $uploadedFile = new UploadedFile($request->fromFiles($image)->getValue());
                    //move the uploaded image to the newly created products images folder
                    if (!$uploadedFile->move($dir, $product->retreive($image))){
                        //onfailure return false
                        return false;
                    }
                }
            }
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
    /**
     * return the savepath for a specific product
     * @return [type] [description]
     */
    private function getSavePath (Product $product) {
    	return public_path . DIRECTORY_SEPARATOR.  product_images_dir . DIRECTORY_SEPARATOR. $product->retreive('id') . DIRECTORY_SEPARATOR;
    }
}