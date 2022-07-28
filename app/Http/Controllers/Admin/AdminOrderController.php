<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Mail\SendMail;
use App\Helpers\Helpers;
use App\Models\OrderDetail;
use App\Models\OrderStatus;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ShippingMethod;
use App\Http\Controllers\Controller;
use App\Jobs\SendReminderEmail;

class AdminOrderController extends Controller
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
        $orders = $this->order->with(['status', 'user:id,name'])
                ->where(function($q) use($request){
                    if($request->order_status_id && $request->order_status_id != -1) $q->where('order_status_id', $request->order_status_id);
                })->latest()->get();
        $order_statuses = OrderStatus::withCount('orders')->get();
        $shippings = ShippingMethod::query()->pluck('shipping_unit');
        return response()->json([
            'orders' => $orders,
            'order_statuses' => $order_statuses,
            'shippings' => $shippings
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
        $carts = $this->getCart($request->user()->id)->get();
        if(isset($carts)){
            $order = new Order();
            $order->fill($request->all());
            $order->order_code = Str::random(8);
            $order->user_id = $request->user()->id;
            $order->order_status_id = 1;
            $order->save();
            foreach($carts as $item){
                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $item->product_id;
                $orderDetail->price = $item->product->price;
                $orderDetail->quantity = $item->quantity;
                $orderDetail->total = $item->quantity * $item->product->price;
                $orderDetail->save();
            }
            $data = [
                'subject' => "Thông báo",
                'message' => '<p>Đặt hàng thành công, mã đơn hàng của bạn là: '. $order->order_code .' . Người bán sẽ liên hệ với bạn trong thời gian sớm nhất!</p>',
                'sender' => request()->user()->name,
            ];
            $this->dispatch(new SendReminderEmail($request->user()->email, $data));
            $this->getCart($request->user()->id)->delete();
            return response()->json([
                'message' => 'Đặt hàng thành công!',
                'order' => $order,
            ], 200);
        }else{
            return response()->json([
                'message' => 'Đặt hàng thất bại! Không tìm thấy thông tin đơn hàng!'
            ], 404);
        }
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
        $order_detail = OrderDetail::with(['product:id,name,image'])->where('order_id', $id)->get();
        return response()->json([
            'order' => $order,
            'order_detail' => $order_detail,
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
        switch ($request->order_status_id) {
            case '2':
                $note = 'Đơn hàng đang được chuẩn bị';
                break;
            case '3':
                $note = 'Đơn hàng đang được vận chuyển';
                break;
            case '4':
                $note = 'Đơn hàng được giao thành công';
                break;
            case '5':
                $note = 'Đơn hàng đã bị hủy ('. $request->note . ')';
                break;
            case '7':
                $note = 'Tiếp nhận hoàn trả đơn hàng';
                break;
            case '9':
                $note = 'Đơn hàng hoàn trả thành công!';
                break;
            default:
                break;
        }
        Helpers::CreateOrderNote($order->id,auth()->guard('admins')->user()->id, $note);

        return response()->json([
            'message' => 'Cập nhật đơn hàng thành công!',
            'order' => $order
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
}
