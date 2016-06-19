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
    
    private $images = ['main_image_link', 'small_image_link'];
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
        return $this->view('cms::products::create.php', $this->fillGetView());
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

        return $this->view('cms::products::create.php', $this->fillPostView([
                'errors' => $validator->getErrors()
            ], ($request->get('mainCategory') ? $request->get('mainCategory')->getValue() : NULL)
        ));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function showEdit(int $id)
    {
        $product        = new Product;
        $product        = $product->with('category')->findOrFail($id);    
        $category       = $product->getRelated('category');
       
        $product->set('mainCategory', $category->retreive('Parent_id'));
        
        return $this->view('cms::products::edit.php', $this->fillPostView([
            'product'   => $product,
        ], $category->retreive('Parent_id')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function put($id, Request $request, Validator $validator)
    {
       $product = new Product;
       $product = $product->with('category')->findOrFail($id); 
       
       $extractImagesFromProduct = function () use ($product) {
           $images = [];
           foreach ($this->images as $image) {
            $images[$image] = $product->retreive($image);
           }
           
           return $images;
       };
       //Ssave a referenve to the old images
       $oldImages       = $extractImagesFromProduct();
       
       if ($this->save($request, $validator, $product, true)) {
            $currentImages = $extractImagesFromProduct();
            
            $file = new File;
            $dir = $this->getSavePath($product);
            if ($this->saveImages($request, $product)) {
                //remove oldimages
                $file->removeAllButFromDir($dir, array_values($currentImages));
                
                $this->redirect()->toRoute('ProductsOverview');
            } else {
                $validator->addError('Fout tijdens het opslaan van de afbeeldingen!');
                //reset old files
                foreach ($oldImages as $name => $file) {
                    $product->set($name, $file);
                }
                
                $product->save();
                //remove any saved files
                $file->removeAllButFromDir($dir, array_values($oldImages));
            }
        }
       
       return $this->view('cms::products::edit.php', $this->fillPostView([
                'errors' => $validator->getErrors(),
                'product' => $product
            ], ($request->get('mainCategory') ? $request->get('mainCategory')->getValue() : NULL)
        ));
    }

    /**
     * Show a delete form confirmation form
     *
     * @param  int  $id
     * @return Response
     */
    public function showDelete($id)
    {
        $product = (new Product)->findorFail($id);
        
        return $this->view('cms::default.delete.php', ['name' => $product->retreive('name')]);
    }
    
    /**
     * Remove a product (softdelete)
     *
     * @param  int  $id
     * @return void
     */
     public function delete ($id) {
         $product = (new Product)->findorFail($id);
         if (!$product->softDelete()){
            throw new Exception("Kon product niet verwijderen!");
         } else {
            $file = new File;
            $file->removeDir($this->getSavePath($product));
            $this->redirect()->toRoute('ProductsOverview');
         }
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
     * Fill the data array with default settings that apply to all (from) get views
     *
     * @param array $data the data array
     * @return array
     */
    private function fillGetView (array $data = []) {
        return array_merge($data, [
            'vatRates' => $this->getVat(),
            'categories' => $this->getCategories()
        ]);
    }
    /**
     * Fill the data array with default settings that apply to all (from) get views
     *
     * @param array $data the data array
     * @return array
     */
    private function fillPostView (array $data = [], $subcategoriesParentid = NULL) {
        $data = $this->fillGetView($data);
        $subCategories = [];
        if ($subcategoriesParentid) {
            $subCategories  = $this->collectionToAssoc($this->getSubCategories((int)$subcategoriesParentid), 'id', 'name');
        }
        
        $data['subCategories'] = $subCategories;
        
        return $data;
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
        return $this->collectionToAssoc($vatRates->getCollection($vatRates->select()), 'id', 'description');
    }
    /**
     * Returns all the 
     */
    private function getCategories () {
        $category = new Category;
        return $this->collectionToAssoc($category->getCollection($category->byName($category->allParent())), 'id', 'name');
    }
    /**
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    private function save (Request $request, Validator $validator, Product $product, $edit = false) {
    	$imageRequirements = ['mime:prefix=image|types=jpg,jpeg,png,gif'];
        if (!$edit) {
            array_unshift($imageRequirements, 'required');
        }
        
        $validator->addRules([
            new Rule('mainCategory', 'Hoofd categorie', ['required']),
            new Rule('Categories_id', 'Sub categorie', ['required']),
            new Rule('name', 'Naam', ['required', 'min:size=2', 'max:size=20']),
            new Rule('artikelnr', 'Artikel nummer', ['required', 'num']),
            new Rule('price', 'Prijs', ['required', 'regex:expression=/^\d*(\.\d+|)?$/'], ['regex' => ':field is geen geldige prijs!']),
            new Rule('vat_rate_id', 'Btw', ['required']),
            new Rule('short_description', 'Korte omschrijving', ['required', 'min:size=10', 'max:size=255']),
            new Rule('detail', 'Omschrijving', ['required', 'min:size=10', 'max:size=3000']),
            new Rule('main_image_link', 'Hoofd afbeelding', $imageRequirements),
            new Rule('small_image_link', 'Kleine afbeelding', $imageRequirements), 
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
        $dir = $this->getSavePath($product);
        
        try {
            $file = new File;
            //throws exception onfailure
            $file->createDir($dir);
       
            foreach ($this->images as $image){
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