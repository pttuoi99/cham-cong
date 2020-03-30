<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Attendance;
use Auth;
use DB;
use Carbon\Carbon;

class DiaryController extends Controller
{
	public function diary_attendance(Request $request)
	{
		$user = Auth::user();
		if ($user) {
			$ago =  Carbon::now()->subDays(7);
            $now = Carbon::now();

			$data = Attendance::where('idno',$user->idno)->whereBetween('date',[$ago,$now])->select('date','timein','timeout','totalhours')->get();
			return response()->json($data);
		}
	}
}

