<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Coach;
use App\Models\CoachLevel;
use App\Enums\UserPosition;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\SendPasswordMail;
use App\Http\Controllers\Controller;

class AdminCoachController extends Controller
{

    protected $coach;
    protected $coachLevel;
    public function __construct(Coach $coach, CoachLevel $coachLevel)
    {
        $this->coach = $coach;
        $this->coachLevel = $coachLevel;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coaches = $this->coach->with(['user', 'coachLevel'])->latest()->paginate(25);
        $coachLevel = $this->coachLevel->get();
        return response()->json([
            'coaches' => $coaches,
            'levels' => $coachLevel,
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
        $coach = new Coach();
        $coach->fill($request->all());
        $user = User::query()->where('email', $request->email)->first();
        if(isset($user)){
            $coach->user_id = $user->id;
            $user->position = UserPosition::Coach;
            $user->avatar = $request->avatar;
            $user->save();
        }else{
            $user = new User();
            $user->username = $request->email;
            $user->email = $request->email;
            $password = Str::random(8);
            $user->password = bcrypt($password);
            $user->name = $request->email;
            $user->position = UserPosition::Coach;
            $user->avatar = $request->avatar;
            $user->save();
        }
        $coach->user_id = $user->id;
        $coach->save();
        $data = [
            'subject' => "Thông báo",
            'content' => '<p>Đăng kí Huấn Luyện Viên thành công bạn có thể đăng nhập vào hệ thống bằng: tên đăng nhập: '. $user->username .' + mật khẩu: ' . $password .'</p>',
            'receiver' => $user->name,
            'sender' => request()->user()->name,
        ];
        $this->dispatch(new SendPasswordMail($user->email,$data));
        return response()->json([
            'message' => 'Thêm mới thành công!',
            'coach' => $coach,
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
        $coach = $this->coach->with(['user', 'coachLevel'])->find($id);
        return response()->json([
            'coach' => $coach,
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
        $coach = $this->coach->find($id);
        $coach->fill($request->all())->update();
        return response()->json([
            'message' => 'Cập nhật thành công!',
            'coach' => $coach,
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

    public function updateAvatarCoach(Request $request, $id)
    {
        $coach = $this->coach->find($id);
        $old_image = $coach->avatar;
        if($request->hasFile('avatar')){
            $fileNameWithExt = $request->file('avatar')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('avatar')->getClientOriginalExtension();
            $fileNameToStoge = time().'_'.$fileName. '.' .$extension;

            $path = $request->file('avatar')->storeAs('public/images', $fileNameToStoge);
            $coach->avatar = $fileNameToStoge;
            $coach->save();

            if(isset($old_image)){
                try {
                    unlink(public_path('storage/images/'.$old_image));
                } catch (\Throwable $th) {
                    //throw $th;
                }
            } 
            return response()->json([
                'msg' => 'storage/images/'.$old_image,
                'coach' => $coach,
            ], 200);
        }
        return response()->json([
            'msg' => 'Not Foud',
        ], 404);
    }

    public function createAvatarCoach(Request $request, $name)
    {
        if($request->hasFile('avatar')){       
            $request->file('avatar')->storeAs('public/images', $name);
            return response()->json([
                'message' => 'create success',
            ], 200);
        }
        return response()->json([
            'message' => 'not found image',
        ], 404);
    }
}
