<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hash;
use App\User;
use Auth;

class UpdatePassController extends Controller
{
    public function update_pass(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $oldpass = $request->oldpass;
            dd($oldpass);
            if (Hash::check($oldpass, $user->password)) {
                if($request->oldpass == $request->newpass){
                echo "Mật khẩu mới không được giống mật khẩu hiện tại!";
            }else{
                $user->password = Hash::make($request->input('newpass'));
                $user->save();
                echo "Thay đổi mật khẩu thành công";
            }
            }else {
                echo "Mật khẩu cũ không đúng";
            }
        }
}
}