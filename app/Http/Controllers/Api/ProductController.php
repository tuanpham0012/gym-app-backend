<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    protected $product;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = $this->product->with(['productType'])
            ->where('deleted', 0)
            ->where('name', 'like', '%'.$request->search.'%')
            ->where(function($q) use($request){
                if(isset($request->type_id) && $request->type_id != -1 ) 
                    $q->where('product_type_id', $request->type_id);
            });
        if(isset($request->filterName) && $request->filterName != -1){
                    if($request->filterName == 0){
                        $products->orderBy('name', 'asc');
                    }else{
                        $products->orderBy('name', 'desc');
                    }
                }
                if(isset($request->filterPrice) && $request->filterPrice != -1){
                    if($request->filterPrice == 0){
                        $products->orderBy('price', 'asc');
                    }else{
                        $products->orderBy('price', 'desc');
                    }
                }
            // ->paginate(30);
        $product_types = ProductType::query()->get();
        return response()->json([ 
            'products' => $products->paginate(30),
            'product_types' => $product_types,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->product->with(['productType'])->find($id);
        return response()->json($product, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
