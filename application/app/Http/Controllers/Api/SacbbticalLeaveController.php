<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\People_leaves;
use Auth;
use DB;

class SacbbticalLeaveController extends Controller
{
	//Tạo giấy nghỉ phép
	public function create_sabbatical(Request $request)
	{
		$user = Auth::user();
		if ($user) {
			$data = new People_leaves;
			$data->reference = $user->reference;
			$data->idno = $user->idno;
			$data->employee = $user->employee;
			$data->typeid = $request->typeid;
			$data->type = $request->type;
			$data->leavefrom = $request->leavefrom;
			$data->leaveto = $request->leaveto;
			$data->returndate = $request->returndate;
			$data->reason = $request->reason;
			$data->status = 0;
			$data->save();
			return response()->json([
				'message'=>'Đã gửi chờ phê duyệt!',
			]);
		}
	}


	// Kiểm duyệt danh sách nghỉ phép
	public function approval_sabbatical(Request $request,$id)
	{
		$user = Auth::user();
		if ($user) {
			if ($user->role_id == 2) {
				$Approval = People_leaves::find($id);
				if ($Approval->status == 0) {
					$Approval->status = $request->input(('status'));
					$Approval->comment = $request->input('comment');
					$Approval->save();

					if ($Approval->status == 1) {
						return response()->json([
							'message'=>'Đã phê duyệt đơn nghỉ phép!',
							'data' => $Approval,
						]);
					}elseif ($Approval->status == 2) {
						return response()->json([
							'message'=>'Đã Từ chối đơn nghỉ phép!',
							'data' => $Approval,
						]);
					} 
				}
			}else{
				echo "Bạn không có quyền duyệt giấy phép!";
			}
		}
	}

	//hiển thị danh sách đăng kí nghỉ phép
	public function show_sabbatical(Request $request)
	{
		$user = Auth::user();
		if ($user) {
			if ($user->role_id == 2) {
				$data = People_leaves::select('employee','type','leavefrom','leaveto','returndate','status','comment')->where('status',0)->get();
				return response()->json($data);
			}else{
				$data = People_leaves::where('idno',$user->idno)->where('status',0)->select('employee','type','leavefrom','leaveto','returndate','status','comment')->get();
				return response()->json($data);
			}
		}
	}

	//hiển thị lịch sử đăng kí nghỉ phép
	public function show_history(Request $request)
	{
		$user = Auth::user();
		if ($user) {
			if ($user->role_id == 2) {
				$data = People_leaves::select('employee','type','leavefrom','leaveto','returndate','status','comment')->where('status',1)->get();
				return response()->json($data);
			}else{
				$data = People_leaves::where('idno',$user->idno)->select('employee','type','leavefrom','leaveto','returndate','reason','status','comment')->where('status',1)->get();
				return response()->json($data);
			}
		}
	}

	//Hiển thị chi tiết đơn nghỉ phép
	public function sabbatical_details(Request $request, $id)
	{
		$user = Auth::user();
		if ($user) {
			$data = People_leaves::select('employee','type','leavefrom','leaveto','returndate','reason','status','comment')->find($id);
			$data1 = People_leaves::select('type','leavefrom','leaveto','returndate','reason')->where('idno',$user->idno)->find($id);
			if ($user->role_id == 2) {
				return response()->json($data);
			}else{
				return response()->json($data1);
			}
		}
	}
}
