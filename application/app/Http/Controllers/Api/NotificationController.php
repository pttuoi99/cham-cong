<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Device_token;
use Auth;

class NotificationController extends Controller
{
	public function save_notification(Request $request)
	{
		$user = Auth::user();
		if($user){
			$device = new Device_token;
			$device->token = $request->token;
			$device->reference = $user->reference;
			if ($device->save()) {
				return response()->json('success', 200);          
			}else{
				return response()->json('error', 500);
			}
		}
	}

	public function show_notification(Request $request)
	{
		$user = Auth::user();
		if ($user) {
			$device =  Device_token::where('reference',$user->reference)->get();
		}
		return response()->json($device);
	}
}

