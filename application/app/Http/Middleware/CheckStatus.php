<?php
/*
* Smart Timesheet: Time and Attendance Management System
* Email: official.smarttimesheet@gmail.com
* Version: 1.0
* Author: Brian Luna
* Copyright 2018 Brian Luna
* Website: https://github.com/brianluna/smarttimesheet
*/
namespace App\Http\Middleware;

use Closure;
use View;
use App\Classes\table;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $status = \Auth::user()->status;
        if ($status==null || $status==0) {
            \Auth::logout();
            return redirect()->route('disabled');
        } 

        $role_id = \Auth::user()->role_id;
        if ($role_id==null || $role_id==0) {
            \Auth::logout();
            return redirect()->route('notfound');
        }

        $dd = json_decode(table::settings()->where('id', 1)->value('db_object'));
        if(isset($dd->key)) {$sh = ($dd->key == null || strlen($dd->key) != 60) ? 2 : 1;} else {$sh = 2;}
        view()->share('var', $sh);
        $request->merge(compact('sh'));
        
        return $next($request);
    }
}
