<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register','Api\AuthController@register');
Route::post('/login','Api\AuthController@login');

Route::group(['middleware'=>'auth:api'],function (){
	
	Route::get('user', 'Api\AuthController@user');
	Route::get('/logout', 'Api\AuthController@logout'); //đăng xuất
	Route::get('/show-info','Api\UpdateInfoController@show_info'); // hiển thị thông tin
	Route::put('/update-info','Api\UpdateInfoController@update_info'); //cập nhật thông tin
	Route::put('/update-pass','Api\UpdatePassController@update_pass'); // cập nhật mật khẩu
	//hiển thị nhật ký điểm danh
	Route::get('/diary-attendance','Api\DiaryController@diary_attendance'); 
	//checkin & checkout
	Route::put('/checkout','Api\CheckController@checkout');
	Route::post('/checkin','Api\CheckController@checkin');
	//tạo - update - show danh sách nghỉ phép
	Route::post('/create-sabbatical','Api\SacbbticalLeaveController@create_sabbatical');
	Route::put('/approval-sabbatical/{id}','Api\SacbbticalLeaveController@approval_sabbatical');
	Route::get('/show-sabbatical','Api\SacbbticalLeaveController@show_sabbatical');
	Route::get('/show-history','Api\SacbbticalLeaveController@show_history');
	Route::get('/sabbatical-details/{id}','Api\SacbbticalLeaveController@sabbatical_details');
	//Báo cáo chấm công
	Route::get('report-list','Api\ReportController@report_list');
	Route::get('report-calendar','Api\ReportController@report_celendar');
	//show token
	Route::post('save-notification','Api\NotificationController@save_notification');
	Route::get('show-notification','Api\NotificationController@show_notification');
});
