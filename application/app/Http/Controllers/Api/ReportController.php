<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Attendance;
use Auth;
use Carbon\Carbon;
use DB;

class ReportController extends Controller
{
	public function report_list(Request $request)
	{
		$user = Auth::user();
		if ($user) {
			// $date = $request->date;
			// $demo = Attendance::where('date',$date)->get();
			// if($date != ''){
			// 	return response()->json($demo);
			// }
			$report =  Attendance::whereMonth('date',Carbon::now()->month)
			->select('idno','date','employee','timein','timeout','totalhours')
			->get();
			$report1 =  Attendance::where('idno',$user->idno)
			->whereMonth('date',Carbon::now()->month)
			->select('idno','date','employee','timein','timeout','totalhours')
			->get();

			if ($user->role_id == 2) {
				return response()->json($report); 
			}else{
				return response()->json($report1);
			}

		}
	}

	public function report_celendar(Request $request)
	{
		$user = Auth::user();
		if ($user) {
			$date = $request->date;
			$demo = DB::table('tbl_people_attendance')->get();
			$a = [];
			foreach($demo as $value){
				$a[] = $value->date;
			}
			$data = Attendance::where('date',$date)->get();
			$data1 = Attendance::where('date',$date)->where('idno',$user->idno)->get();
			if (in_array($date, $a)) {
				if ($user->role_id == 2) {
					return response()->json($data);
				}else{
					return response()->json($data1);
				}
			}else{
				return response()->json([
					'message' => 'Ngày bạn chọn không có dữ liệu!'
				]);
			}
		}
	}
}
