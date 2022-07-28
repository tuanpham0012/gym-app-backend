<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    protected $notification;
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $notifications = $this->notification->with(['user:id,name,avatar'])->where('user_id', $request->user()->id)
                ->where(function($query) use($request){
                    if( isset($request->read) && strtoupper($request->read) === "TRUE" ) $query->where('read', 0);
                })
                ->latest()->get();
        $count = $this->notification->where('user_id', $request->user()->id)->where('read', 0)->get()->count();
        return response()->json([
            'notifications' => $notifications,
            'count' => $count,
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
        $notification = $this->notification->find($id);
        $notification->read = 1;
        $notification->save();
        return response()->json([
            'message' => 'Cập nhật thành công!!!',
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
        if($id == request()->user()->id){
            $notification = $this->notification->where('user_id', request()->user()->id)->update(['read' => 1]);
            return response()->json([
                'message' => 'Cập nhật thành công!!!',
            ], 200);
        }else{
            return response()->json([
                'message' => 'Có lỗi xảy ra!'
            ], 500);
        }
        
    }
}
