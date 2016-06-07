<?php
namespace App\Controllers\Frontend;
use App\Controllers\Controller;
use IPHP\View\ViewResponse;

use App\Category;
use App\Product;

class CategoriesController extends Controller
{
    /**
     * Display a listing of main categories.
     *
     * @return Response
     */
    public function showMainCategories()
    {
        $category = new Category;
        
        return new ViewResponse("frontend::categories::main.php", [
            'categories' => $category->getCollection( $category->byName($category->allParent()) ) 
        ]);
    }
    /**
     * Display a listing of subcategories.
     *
     * @return Response
     */
    public function showSubcategories(int $category_id)
    {
        $category = new Category;

        $mainCategory = $category->findOrFail($category_id);

        return new ViewResponse("frontend::categories::sub.php", [
            'categories' => $category->getCollection( $category->allFromParent($category_id) ),
            'title' => 'Category: '. $mainCategory->retreive('name'),
            'mainCategory' => $mainCategory
        ]);
    }
    /**
     * [showArticles description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function showProducts(int $category_id, int $sub_category_id)
    {
        return new ViewResponse("frontend::categories::products.php", [
            'products' => $product->get( $product->fromCategory($subcategory) )
        ]);
    }

}