<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Helpers\Helpers;
use App\Models\OrderDetail;
use App\Models\OrderStatus;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ShippingMethod;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{

    protected $order;
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = $this->order->with(['status'])
                ->where('user_id', $request->user()->id)
                ->where(function($q) use($request){
                    if($request->order_status_id && $request->order_status_id != -1) $q->where('order_status_id', $request->order_status_id);
                })->latest()->get();
        $orderStatuses = OrderStatus::all();
        $shippings = ShippingMethod::query()->pluck('shipping_unit');
        return response()->json([
            'orders' => $orders,
            'order_statuses' => $orderStatuses,
            'shippings' => $shippings,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = new Order();
            $order->fill($request->all());
            $order->order_code = Str::random(8);
            $order->user_id = $request->user()->id;
            $order->order_status_id = 1;
            $order->save();
            foreach($request->carts as $item){
                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $item['product_id'];
                $orderDetail->price = $item['product']['price'];
                $orderDetail->quantity = $item['quantity'];
                $orderDetail->total = $item['quantity'] * $item['product']['price'];
                $orderDetail->save();
                Cart::find($item['id'])->delete();
            }
            Helpers::CreateOrderNote($order->id, 1, '?????t h??ng th??nh c??ng!');          
            return response()->json([
                'message' => '?????t h??ng th??nh c??ng!',
                'order' => $order,
            ], 200);
        // if(isset($carts)){
            
        // }else{
        //     return response()->json([
        //         'message' => '?????t h??ng th???t b???i! Kh??ng t??m th???y th??ng tin ????n h??ng!'
        //     ], 404);
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = $this->order->with(['status', 'method', 'notes'])->find($id);
        $orderDetail = OrderDetail::with(['product:id,name,image'])->where('order_id', $id)->get();
        return response()->json([
            'order' => $order,
            'order_detail' => $orderDetail,
        ], 200);
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
        $order = $this->order->find($id);
        $order->fill($request->all())->update();
        $note = '';
        $message = '';
        switch ($request->order_status_id) {
            case '5':
                $note = 'Ng?????i ?????t ???? h???y ????n h??ng ( '. $request->note . ' )';
                $message = 'H???y ????n h??ng th??nh c??ng!';
                break;
            case '6':
                $note = 'Ng?????i ?????t y??u c???u ho??n ????n h??ng ( '. $request->note . ')';
                $message = 'Y??u c???u ho??n ????n h??ng ???? ???????c g???i! Vui l??ng ch??? ph???n h???i t??? c???a h??ng!';
                break;
            case '8':
                $note = '????n h??ng ho??n tr??? ??ang ???????c v???n chuy???n';
                $message = 'C???p nh???t ????n h??ng th??nh c??ng!';
                break;
            default:
                $message = 'C???p nh???t ????n h??ng th??nh c??ng!';
                break;
        }
        Helpers::CreateOrderNote($order->id, 1, $note);
        return response()->json([
            'message' => $message,
        ], 200);
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

    public function getCart($user_id)
    {
        return $cart = Cart::with('product:id,price')->where('user_id', $user_id);
    }
}
