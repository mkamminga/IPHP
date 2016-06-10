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
            $products   = $product->with('vat')
                                    ->get($product->fromCategory($sub_category_id));
            
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
            $product   = (new Product)->with('vat')->findOrFail($product_id);
            
            return $this->view('frontend::products::item.php', [
                'category' => $category,
                'product' => $product->contents(),
            ]);
        }
    }
    /**
     * Display a listing of products filtered by a search query
     *
     * @return Response
     */
    public function searchProduct(Request $request)
    {
        $productModel = new Product;
        if ($request->get('q') && !$request->get('q')->isEmpty()) {
            $searchable = $productModel->searchable($request->get('q')->getValue());
        } else {
            $searchable = $productModel->select();
        }

        $products   = $productModel->with('category')
                                    ->with('vat')
                                    ->get($searchable->orderBy('name'));

        return $this->view('frontend::products::search.php', [
            'products' => $products
        ]);
    }

    private function verifySubCategory (int $category_id, Category $category):bool {
        if ($category_id != $category->retreive('Parent_id')) {
            throw new \Exception("Category id does not match!");
        }

        return true;
    }
}