<?php
/**
 * @author Alhric Lacle <alhriclacle@gmail.com>
 * @project Web3
 * @created 24-Jun-15 3:31 PM
 */



namespace App\Http\Controllers\Frontend;
use App\Http\Models\Categorie;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;


class Products extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($category_id)
    {
        $products = Product::withCategory($category_id)->paginate(3);
        $category = Categorie::findOrFail($category_id);
        if(count($products)<1)
        {
            return redirect()->route('categories');
        }
        $first = $products[rand(0,(count($products)-1))];

        return view('customerPages.products')->with('products',$products)->with('category',$category)->with('first',$first);
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

        $first = $products[rand(0,(count($products)-1))];
        $request->session()->put('firstResult',$first);
        $request->session()->put('searchResults',$products);

        return response()->json(['nothing'=>'found some stuff','found'=>1]);

    }

    public function displayResults(Request $request)
    {

        $products = $request->session()->get('searchResults');
        $first = $request->session()->get('firstResult');
        if($products==null||$first==null)
        {
            return redirect()->route('categories');
        }
        return view('customerPages.resultspage')->with('products',$products)->with('first',$first);
    }
}