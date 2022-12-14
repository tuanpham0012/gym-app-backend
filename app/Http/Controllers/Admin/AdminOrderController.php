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
                'subject' => "Th??ng b??o",
                'message' => '<p>?????t h??ng th??nh c??ng, m?? ????n h??ng c???a b???n l??: '. $order->order_code .' . Ng?????i b??n s??? li??n h??? v???i b???n trong th???i gian s???m nh???t!</p>',
                'sender' => request()->user()->name,
            ];
            $this->dispatch(new SendReminderEmail($request->user()->email, $data));
            $this->getCart($request->user()->id)->delete();
            return response()->json([
                'message' => '?????t h??ng th??nh c??ng!',
                'order' => $order,
            ], 200);
        }else{
            return response()->json([
                'message' => '?????t h??ng th???t b???i! Kh??ng t??m th???y th??ng tin ????n h??ng!'
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
                $note = '????n h??ng ??ang ???????c chu???n b???';
                break;
            case '3':
                $note = '????n h??ng ??ang ???????c v???n chuy???n';
                break;
            case '4':
                $note = '????n h??ng ???????c giao th??nh c??ng';
                break;
            case '5':
                $note = '????n h??ng ???? b??? h???y ('. $request->note . ')';
                break;
            case '7':
                $note = 'Ti???p nh???n ho??n tr??? ????n h??ng';
                break;
            case '9':
                $note = '????n h??ng ho??n tr??? th??nh c??ng!';
                break;
            default:
                break;
        }
        Helpers::CreateOrderNote($order->id,auth()->guard('admins')->user()->id, $note);

        return response()->json([
            'message' => 'C???p nh???t ????n h??ng th??nh c??ng!',
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
