<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Attendance;
use App\User;
use Carbon\Carbon;
use Auth;
use DB;

class CheckController extends Controller
{

	public  function checkin(Request $request){

		$user = Auth::user();
		if ($user) {
			$checkin = new Attendance;
			$checkin->idno 		= $user->idno;
			$checkin->employee 	= $user->name;
			$checkin->reference = $user->reference;
			$checkin->status_timein = 'ok';
			$checkin->timein 	= Carbon::now()->toTimeString();
			$checkin->date 		= Carbon::now()->toDateString();

			$data = DB::table('tbl_people_attendance')->where('idno',$user->idno)->where('date',$checkin->date)->first();

			if (isset($data->date)) {
				return response()->json([
					'message' => 'Hôm nay bạn đã check in'
				]);
			}else{
				if ($checkin->save()) {
					return response()->json('success', 200);      
					return response()->json($checkin);    
				}else{
					return response()->json('error', 500);
				}
			}			
		}
	}

	public function checkout(Request $request)
	{
		$user = Auth::user();
		if ($user) {
			$date = Carbon::now()->toDateString();
			$checkout =  Attendance::where('idno',$user->idno)->where('date',$date)->first();
			if(!isset($checkout->date))
			{
				return response()->json([
					'message' => 'Bạn cần phải checkin trước khi checkout'
				]);
			}else
			if ($checkout->status_timeout == 'ok') {
				return response()->json([
					'message' => 'Hôm nay bạn đã checkout'
				]);
			}else
			if ($checkout->status_timeout == '') {
				$checkout->timeout = Carbon::now()->toTimeString();
				$timein = Carbon::createFromFormat('H:i:s', $checkout->timein);
				$timeout = Carbon::createFromFormat('H:i:s',Carbon::now()->toTimeString());
				$th = $timein->diffInHours($timeout);
				$tm = floor(($timein->diffInMinutes($timeout) - (60 * $th)));
				$total = $th.".".$tm;
				$checkout->totalhours = $total;
				$checkout->status_timeout = 'ok';
				$checkout->save();
				return response()->json($checkout); 
			}
		}
	}
}