<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    protected $ticket;
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = $this->ticket->with(['ticketType','status'])->where('user_id', request()->user()->id)->get();
        return response()->json([
            'tickets' => $tickets,
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
        $ticket = new Ticket();
        $ticket->user_id = $request->user()->id;
        $ticket->member_code = Str::random(8);
        $ticket->name = $request->name;
        $ticket->phone_number = $request->phone_number;
        $ticket->cost = $request->cost;
        $ticket->registration_date = $request->registration_date;
        $ticket->duration = $request->quantity * 30;
        $ticket->ticket_type_id = $request->type_id;
        $ticket->ticket_status_id = 1;
        $ticket->expiration_date = Carbon::createFromFormat('Y-m-d', $request->registration_date)->addDays($ticket->duration);
        $ticket->save();
        $message = '<p>Vui lòng thanh toán qua ngân hàng với nội dung:<strong> Tên người đăng kí + sdt + Mã giao dịch</strong> </p><br>';
        return response()->json([
            'message' => $message.Helpers::returnBanking(),
            'status' => 'Đăng kí thành công!',
            'ticket' => $ticket->member_code,
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
        $ticket = $this->ticket->with(['ticketType','user:id,name','status'])->find($id);
        return response()->json([
            'ticket' => $ticket,
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

    public function checkTicket(Request $request){
        $ticket = $this->ticket->where('user_id', $request->user()->id)->get();
        if($ticket->count() > 0 ){
            return response()->json([
                'message' => 'Bạn đã đăng kí vé tập trước đây. Bạn có muốn đăng kí vé tập mới?',
                'ticket' => $ticket,
            ], 201);
        }
        else{
            return response()->json([
                'message' => 'Continue Registration!!!',
            ], 200);
        }
    }
}
