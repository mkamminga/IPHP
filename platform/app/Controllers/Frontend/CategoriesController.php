<?php
namespace App\Controllers\Frontend;
use App\Controllers\Controller;
use IPHP\View\ViewResponse;

use App\Category;
use App\Product;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function showSubcategories(Category $category)
    {
        return new ViewResponse("frontend/categories.php", [
            'categories' => $category->get( $category->all() )
        ]);
    }
    /**
     * [showArticles description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function showProducts(Product $product, int $subcategory)
    {
        return new ViewResponse("frontend/products.php", [
            'products' => $product->get( $product->fromCategory($subcategory) )
        ]);
    }

}