<?php
namespace App\Controllers\Frontend;

use IPHP\Http\Request;

use App\Controllers\Controller;
use App\Product;
use App\Category;

class ProductsController extends Controller
{
    /**
     * Display a listing of products linked to the subcategory.
     *
     * @return Response
     */
    public function showProducts(int $category_id, int $sub_category_id)
    {
        $product    = new Product;
        $category   = (new Category)->findOrFail($sub_category_id);
        if ($this->verifySubCategory($category_id, $category)){
            $products   = $product->getCollection($product->fromCategory($sub_category_id));
            
            return $this->view('frontend::products::overview.php', [
                'category'  => $category,
                'products'  => $products,
                'title'     => 'Producten van '. $category->retreive('name')
            ]);
        }
    }
    /**
     * Display a listing of products linked to the subcategory.
     *
     * @return Response
     */
    public function showProduct (int $category_id, int $sub_category_id, int $product_id) {
        $category   = (new Category)->findOrFail($sub_category_id);
        if ($this->verifySubCategory($category_id, $category)){
            $product   = (new Product)->findOrFail($product_id);
            
            return $this->view('frontend::products::item.php', [
                'category' => $category,
                'product' => $product->contents(),
            ]);
        }
    }


    public function ajax($id)
    {
        $product = Product::findOrFail($id);
        return response()->json(['product'=>$product]);
    }

    public function searchProduct(Request $request, $value)
    {
        $products = Product::withSearchable($value)->get();

        if(count($products)<1 || $products ==null)
        {
            $nothing = 'No products found';
            return response()->json(['nothing'=>$nothing,'found'=>0]);
        }

    }

    private function verifySubCategory (int $category_id, Category $category):bool {
        if ($category_id != $category->retreive('Parent_id')) {
            throw new \Exception("Category id does not match!");
        }

        return true;
    }
}