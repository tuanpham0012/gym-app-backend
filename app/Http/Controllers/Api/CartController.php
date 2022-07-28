<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use function PHPUnit\Framework\isEmpty;

class CartController extends Controller
{

    protected $cart;
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carts = $this->cart->with('product:id,name,price,image,product_type_id')->where('user_id', $request->user()->id)->get();
       
        return response()->json([ 'carts' => $carts], 200);
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
        $cart = $this->checkCart($request->user()->id, $request->product_id);
        if(!empty($cart)){
            $cart->quantity = $cart->quantity + $request->quantity;
            $cart->save();
        }else{
            $cart = new Cart();
            $cart->fill($request->all());
            $cart->user_id = $request->user()->id;
            $cart->save();
        }

        return response()->json([
            'message' => 'Thêm sản phẩm vào giỏ hàng thành công!',
            'cart' => $cart,
        ], 200);
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
        $cart = $this->cart->with('product:id,name,price,image')->find($id);
        if(isset($cart)){
            $cart->quantity = $request->quantity;
            $cart->save();

            return response()->json([
                'message' => 'Cập nhật thành công!',
                'cart' => $cart,
            ], 200);
        }

        return response()->json([
            'message' => 'Không tìm thấy bản ghi!',
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cart = $this->cart->find($id)->delete();
        return response()->json([
            'message' => 'Xóa sản phẩm thành công!',
        ], 200);
    }

    public function checkCart($user_id, $product_id){
        return $this->cart->where('user_id', $user_id)->where('product_id', $product_id)->first();
    }

    public function deleteAllCart(Request $request){
        $carts = $this->cart->where('user_id', $request->user()->id)->delete();
        return response()->json([
            'message' => 'Xóa giỏ hàng thành công!'
        ], 200);
    }
}
