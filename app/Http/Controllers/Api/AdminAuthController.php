<?php

namespace App\Http\Controllers\Api;

use App\Enums\AdminPosition;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    /**
     * @param \Illuminate\Http\Request  $request
     * @return token
     */
    public function adminLogin(Request $request){
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

            $admin = Admin::where('username', $request->username)->first();
            if (!$admin | !Hash::check($request->password, $admin->password, [])) {
                return response()->json([
                    'message' => 'Sai tên tài khoản hoặc mật khẩu',
                ], 500);
            }

            $token = $admin->createToken('myApp')->plainTextToken;
            $admin->position_name = AdminPosition::getKey($admin->position);
            return response()->json([
                'token' => $token,
                'token_type' => 'Bearer',
                'info' => $admin,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json($th, 500);
        } 
    }


    public function userLogout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout success'
        ], 200);
    }

    public function getInfo(Request $request){
        $info = auth()->guard('admins')->user();
        $info->position = AdminPosition::getKey($info->position);
        return response()->json([
            'info' => $info,
        ], 200);
    }
}
