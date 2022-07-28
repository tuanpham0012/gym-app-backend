<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserPosition;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    /**
     * @param \Illuminate\Http\Request  $request
     * @return token
     */
    public function userLogin(Request $request){
        try {
            $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);
            $credentials = request(['username', 'password']);

            // if(!Auth::attempt($credentials)){
            //     return response()->json([
            //         'message' => 'Unauthorized'
            //     ], 500);
            // }

            $user = User::where('username', $request->username)->first();
            if (!$user || !Hash::check($request->password, $user->password, [])) {
                return response()->json([
                    'message' => 'Sai tên tài khoản hoặc mật khẩu',
                ], 404);
            }

            $token = $user->createToken('myApp')->plainTextToken;
            $user->position_name = UserPosition::getKey($user->position);
            return response()->json([
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json($th, 500);
        } 
    }

    public function userRegister(Request $request){
        $request->validate([
            'username' => 'required|string|unique:users',
            'email' => 'required',
            'name' => 'required|string',
            'password' => 'required|confirmed|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'position' => UserPosition::User,
        ]);

        $token = $user->createToken('myApp')->plainTextToken;
        $user->position = UserPosition::getKey($user->position);

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Đăng kí thành công!'
        ], 200);
    }

    public function userLogout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout success'
        ], 200);
    }

    public function getInfo(Request $request){
        $user = $request->user();
        $user->position_name = UserPosition::getKey($user->position);
        return response()->json([
            'user' => $user,
        ], 200);
    }

    public function update(Request $request)
    {
        $user = User::query()->find($request->user()->id);
        $user->fill($request->all())->update();
        return response()->json([
            'message' => 'Cập nhật thông tin thành công!',
            'info' => $user,
        ], 200);
    }

    public function updateAvatar(Request $request)
    {
        $user = User::query()->find($request->user()->id);
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

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|string|min:6',
        ]);

        $user = User::find($request->user()->id);
        if( !$user || !Hash::check($request->oldPassword, $user->password, [])){
            return response()->json([
                'message' => 'Đổi mật khẩu không thành công! Mật khẩu cũ không đúng!',
                'user' => $user,
            ], 404);
        }
        try {
            $user->password = bcrypt($request->password);
            $user->save();
            $request->user()->tokens()->delete();
            return response()->json([
                'message' => 'Đổi mật khẩu thành công!',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th, 500);
        }
        
    }
}
