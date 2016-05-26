<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Product;
use App\Http\Models\Categorie;
use App\Http\Traits\ReturnAssoc;
use Validator;
use Storage;
use Config;

class Products extends Controller
{
    use ReturnAssoc;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {

        $inputs = $request->all();
        $products = Product::with('categories');
        if (isset($inputs['artikelnr']) && !empty($inputs['artikelnr'])) {
            $products = $products->withArtnr($inputs['artikelnr']);
        }

        $products = $products->orderBy('name')->paginate(15);

        return view('cms.products.overview', ['products' => $products])->withInput($inputs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $vatSettings = $this->getProcessedVat();
        $categories = $this->getProcessedCategories();
        
        return view('cms.products.create', [
            'categories' => $categories, 
            'vat' => $vatSettings,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
  		$validator = $this->getValidator($requestData);

        if (!$validator->fails()) {

            $product = new Product();
            $product->Categories_id = $requestData['category'];
            $product->name = $requestData['name'];
            $product->artikelnr = $requestData['artikelnr'];
            $product->price = $requestData['price'];
            $product->vat = $requestData['vat'];
            $product->short_description = $requestData['short_description'];
            $product->detail = $requestData['detail'];
            $product->main_image_link = 'main.' . $request->file('main_image_link')->guessExtension();
            $product->small_image_link = 'small.' . $request->file('small_image_link')->guessExtension();
          
            //var_dump(file_get_contents($request->file('main_image_link')->getRealPath()));
            $basePath = $this->getImageBasePath($product->artikelnr);
            $request->file('main_image_link')->move($basePath, $product->main_image_link);
            $request->file('small_image_link')->move($basePath, $product->small_image_link);

            if ($product->save()){
                return redirect()->route('beheer.products.index');
            } else {
                $validator->errors()->add('main', 'Er is een onbekende fout opgetreden tijdens het opslaan!');
            }
        }

        return redirect()->route('beheer.products.create')
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
        $product = Product::find($id);


        return view('cms.products.delete', ['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $vatSettings = $this->getProcessedVat();
        $product = Product::find($id);

        if (!$product){
            throw new Exception("Product not found!");
        }

        $categories = $this->getProcessedCategories();

        return view('cms.products.edit', [
            'product' => $product, 
            'categories' => $categories, 
            'vat' => $vatSettings,
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
        $requestData = $request->all();
        $validator = $this->getValidator($requestData, false, $id);

        if (!$validator->fails()) {
            $product = Product::find($id);
            if (!$product) {
                throw new Exception("Product not found!");
            } 

            $product->Categories_id = $requestData['category'];
            $product->name = $requestData['name'];
            $product->artikelnr = $requestData['artikelnr'];
            $product->price = $requestData['price'];
            $product->vat = $requestData['vat'];
            $product->short_description = $requestData['short_description'];
            $product->detail = $requestData['detail'];

            $basePath = $this->getImageBasePath($product->artikelnr);
            if ($request->hasFile('main_image_link')) {
            	$oldname = $product->main_image_link;
            	$this->replaceImage($basePath, $oldname, $request->file('main_image_link'), ($product->main_image_link = 'main.' . $request->file('main_image_link')->guessExtension()));
            }

            if ($request->hasFile('small_image_link')) {
            	$oldname = $product->small_image_link;
            	$this->replaceImage($basePath, $oldname, $request->file('small_image_link'), ($product->main_image_link = 'small.' . $request->file('small_image_link')->guessExtension()));
            }

            if ($product->save()){
                return redirect()->route('beheer.products.index');
            } else {
                $validator->errors()->add('main', 'Er is een onbekende fout opgetreden tijdens het opslaan!');
            }

        }

        return redirect()->route('beheer.products.edit', ['id' => $id])
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
        if (($product = Product::find($id))) {
            $product->delete();
        }
        return redirect()->route('beheer.products.index');
    }
    /**
     * Get all the current categories and return them in an [id=>name] format
     * 
     * @return [type] [description]
     */
    private function getProcessedCategories () {
        $parsedCategories = [];
        $categories = Categorie::all();
        foreach ($categories as $category) {
            $parsedCategories[$category->id] = $category->name;
        }

        return $parsedCategories;
    }
    /**
     * [getProcessedVat description]
     * @return [type] [description]
     */
    private function getProcessedVat() {
        $vat = Config::get('static_values.vat');

        return $this->getAssocValues($vat);
    }
    /**
     * [getValidator description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    private function getValidator (array $requestData, $requireImages = true, $id = 0) {
    	$rules = [
            'category' => 'required',
            'name' => 'required|max:20',
            'artikelnr' => 'required|max:20|unique:products,artikelnr,'. $id,
            'price' => 'required|regex:/^\d*(\.\d{2})?$/',
            'vat' => 'required',
            'short_description' => 'required|min:10|max:255',
            'main_image_link' => 'max:10000|mimes:jpg,jpeg,png,gif',
            'small_image_link' => 'max:10000|mimes:jpg,jpeg,png,gif',
            'detail' => 'required|min:20'
        ];

        if ($requireImages) {
        	$rules['main_image_link'] = 'required|' . $rules['main_image_link'];
        	$rules['small_image_link'] = 'required|' . $rules['small_image_link'];
        }

    	return Validator::make($requestData, $rules);
    }
    /**
     * [getImageBasePath description]
     * @return [type] [description]
     */
    private function getImageBasePath ($artikelnr) {
    	return public_path() . '/images/products/'. $artikelnr . '/';
    }

    private function replaceImage ($basePath, $oldFile, $file, $newFile) {
    	unlink($basePath . $oldFile);
    	$file->move($basePath, $newFile);
    }
}
