<?php
namespace App\Http\Controllers\Frontend;


class Categories extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data = Categorie::withProductThumb()->havingProducts()->get();
        return view('customerPages.categories')->with('categories',$data);
    }

}