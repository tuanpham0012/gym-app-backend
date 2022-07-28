<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TicketStatus;

class AdminTicketController extends Controller
{
    protected $ticket;
    protected $status;
    public function __construct(Ticket $ticket, TicketStatus $status)
    {
        $this->ticket = $ticket;
        $this->status = $status;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tickets = $this->ticket->with(['ticketType', 'user:id,name','status'])
            ->where(function($q) use($request){
                if(isset($request->status_id) && $request->status_id != -1){
                    return $q->where('ticket_status_id', $request->status_id);
                }
            })
            ->latest()->get();
        $statuses = $this->status->get();
        return response()->json([
                'tickets' => $tickets,
                'statuses' => $statuses,
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
        $ticket = $this->ticket->find($id);
        $ticket->fill($request->all());
        $ticket->save();
        return response()->json([
            'message' => 'Cập nhật thành công!',
            'ticket' => $ticket,
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
