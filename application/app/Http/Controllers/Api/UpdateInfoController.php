<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;

class UpdateInfoController extends Controller
{
    

    public function show_info(Request $request)
    {
      
        if (Auth::user()) {
            $user = $request->user();
            $data = [];
            $data['name'] = $user->name;
            $data['email'] = $user->email;
            if ($user->status == 1) {
                $data['status'] = "Hoạt động";
            }else{
                $data['status'] = "Khóa";
            }

            if ($user->role_id == 1) {
                $data['role_id'] = "Quản lý";
            }else{
                $data['role_id'] = "Nhân viên";
            }
           
            return response()->json($data);
        }
    }
        
        public function update_info(Request $request)
        {
            $user = Auth::user();
            if ($user) {
                $user->name = $request->input('name');
                $user->email = $request->input('email');

                $user->save();
                return response()->json($user);
            }

        }
}
