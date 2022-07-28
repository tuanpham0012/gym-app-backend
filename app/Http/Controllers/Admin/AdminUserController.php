<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Mail\SendMail;
use App\Enums\UserPosition;
use Illuminate\Http\Request;
use App\Jobs\SendReminderEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class AdminUserController extends Controller
{
    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = $this->user
            ->where(function($query) use ($request){
                $query->where('name', 'like','%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            })
            ->where(function($query) use($request){
                if(isset($request->position) && $request->position != -1 ){
                    $query->where('position', $request->position);
                    }
            })
            ->latest()->paginate(30);
        foreach($users as $user){
            $user->position_name = UserPosition::getKey($user->position);
        }
        $positions = array();
        foreach(UserPosition::asArray() as $key => $position){
            $obj = [
                'id' => $position,
                'position' => $key,
            ];
            array_push($positions,$obj);
        }
        return response()->json([
            'users' => $users,
            'positions' => $positions,
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
        $user = $this->user->with(['orders.status', 'tickets.ticketType', 'tickets.status'])->find($id);
        $user->position_name = UserPosition::getKey($user->position);
        // $data = [
        //     'subject' => "test send mail turn 2",
        //     'content' => '<p>Thông báo tạm ngừng dịch vụ hỗ trợ di chuyển không thời hạn</p><p>Lý do: Đang cập nhât...</p><p>Thông báo có hiệu lực từ ngày 22/08/2022.</p><p>Cảm ơn quý khách hàng đã sử dụng dịch vụ của chúng tôi! Thân ái!!</p>',
        //     'receiver' => $user->name,
        //     'sender' => request()->user()->name,
        // ];
        // $this->dispatch(new SendReminderEmail($user->email,$data));
        return response()->json([
            'user' => $user,
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
        $user = $this->user->find($id);
        $user->fill($request->all())->update();
        return response()->json([
            'message' => 'Cập nhật thông tin thành công!',
            'user' => $user,
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

    public function updateImageUser(Request $request, $id)
    {
        $user = $this->user->find($id);
        $old = $user->avatar;
        if($request->hasFile('avatar')){
            $fileNameWithExt = $request->file('avatar')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('avatar')->getClientOriginalExtension();
            $fileNameToStoge = time().'_'.$fileName. '.' .$extension;

            $path = $request->file('avatar')->storeAs('public/images', $fileNameToStoge);
            $user->avatar = $fileNameToStoge;
            $user->save();
            if(isset($old)){
                try {
                    unlink(public_path('storage/images/'.$old));
                } catch (\Throwable $th) {
                }
            }
            return response()->json(['message' => 'Cập nhật thành công!', 'user' => $user], 200);
        }
        return response()->json([ 'message' => 'Không tìm thấy thông tin'], 404);
    }
}
