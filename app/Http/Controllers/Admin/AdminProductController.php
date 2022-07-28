<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminProductController extends Controller
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
            ->where(function($query) use ($request){
                $query->where('name', 'like','%'.$request->search.'%')
                    ->orWhere('product_code', 'like', '%'.$request->search.'%');
                })
            ->where('deleted', 0)->where(function($q) use($request){
                if(isset($request->product_type_id) && $request->product_type_id != -1 ) $q->where('product_type_id', $request->product_type_id);
            })
            ->orderBy('product_type_id', 'asc')->latest()->paginate(25);
        $productTypes = ProductType::query()->get();
        return response()->json([ 
            'products' => $products,
            'product_types' => $productTypes,
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
        $product = new Product();
        $product->fill($request->all());
        $product->product_code =  Str::random(6);
        $product->save();
        if($product){
            return response()->json([
                'message' => 'Thêm sản phẩm mới thành công!',
                'product' => $product,
            ], 200);
        }
        return response()->json([
            'message' => 'Có lỗi xảy ra! Vui lòng thử lại!'
        ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $product = $this->product->find($id);
        if($product){
            $product->fill($request->all())->update();
            return response()->json([
                'message' => 'Cập nhật sản phẩm thành công!',
                'product' => $product,
            ], 200);
        }else{
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = $this->product->find($id);
        if($product->deleted == 0){
            $product->deleted = 1;
            $product->save();
        }else{
            $product->delete();
        }
        
        return response()->json([
            'message' => 'Xóa sản phẩm thành công!',
        ], 200);
    }

    public function updateImageProduct(Request $request, $id)
    {
        $product = $this->product->find($id);
        $image_link = $product->image;
        if($request->hasFile('image')){
            $fileNameWithExt = $request->file('image')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStoge = time().'_'.$fileName. '.' .$extension;

            $path = $request->file('image')->storeAs('public/images', $fileNameToStoge);
            $product->image = $fileNameToStoge;
            $product->save();

            if(isset($image_link)){
                try {
                    unlink(public_path('storage/images/'.$image_link));
                } catch (\Throwable $th) {
                    //throw $th;
                }
            } 
            return response()->json([
                'msg' => 'storage/images/'.$image_link,
                'product' => $product,
            ], 200);
        }
        return response()->json([
            'msg' => $image_link,
        ], 404);
    }

    public function createImageProduct(Request $request, $name)
    {
        if($request->hasFile('image')){       
            $request->file('image')->storeAs('public/images', $name);
            return response()->json([
                'message' => 'create success',
            ], 200);
        }
        return response()->json([
            'message' => 'not find image',
        ], 404);
    }
}
