<?php
/*
* Smart Timesheet: Time and Attendance Management System
* Email: official.smarttimesheet@gmail.com
* Version: 1.0
* Author: Brian Luna
* Copyright 2018 Brian Luna
* Website: https://github.com/brianluna/smarttimesheet
*/
namespace App\Http\Controllers;
use DB;
use Carbon\Carbon;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClockController extends Controller
{
    
    public function clock()
    {
        $clock_comment = table::settings()->value('clock_comment');
        $reference = \Auth::user()->reference;
        $schedules = table::schedules()->where('reference', $reference)->where('archive', 0)->get();

        return view('clock', compact('clock_comment', 'schedules'));
    }

    public function add(Request $request)
    {
        $idno = $request->user()->idno;
        $type = $request->type;
        $schedule_id = $request->schedule_id;
        $date = date('Y-m-d');
        $time = date('h:i:s A');
        $comment = strtoupper($request->clock_comment);
        $ip = $request->ip();

        //check schedules exists
        
        if($schedule_id === '0'){
            return response()->json([
                "error" => "Please add schedule!"
            ]);
        }

        // check the valid time
        
        $sched = table::schedules()->where('id', $schedule_id)->where('archive', 0)->select('intime', 'outime')->first();
        $intime = $sched->intime;
        $outime = $sched->outime;

        $now = date("H.i", strtotime($time));
        $intime = date("H.i", strtotime($intime));
        $outime = date("H.i", strtotime($outime));
        
        $condition1 = $intime - $now;
        $condition2 = $outime - $now;
        if(!($condition1 < 0.7 && $condition2 > 0)){
            return response()->json([
                "error" => "The time is not valid to TimeIn/Out!"
            ]);
        }

        // clock-in comment feature
        $clock_comment = table::settings()->value('clock_comment');
        if ($clock_comment == 1) {
            if ($request->clock_comment == NULL ) {
                return response()->json([
                    "error" => "Please provide your comment!"
                ]);
            }
        }

        // ip resriction
        $ipallow = table::settings()->value('ipallow');
        if ($ipallow != NULL) {
            $ips = explode(",", $ipallow);
            if((in_array($ip, $ips) == false)) {
                $msge = "Whoops! You are not allowed to Clock In or Out from your IP address ".$ip;
                return response()->json([
                    "error" => $msge,
                ]);
            }
        } 

        $employee_id = table::companydata()->where('idno', $idno)->value('reference');
        $emp = table::people()->where('id', $employee_id)->first();
        $lastname = $emp->lastname;
        $firstname = $emp->firstname;
        $mi = $emp->mi;
        $gender = $emp->gender;
        $civilstatus = $emp->civilstatus;
        $employee = mb_strtoupper($lastname.', '.$firstname.' '.$mi);

        if ($type == 'Time In') {
            $has = table::attendance()->where([['idno', $idno],['date', $date], ['schedule_id', $schedule_id]])->exists();

            if ($has == 1) {
                $hti = table::attendance()->where([['idno', $idno],['date', $date]])->value('timein');
                $hti = date('h:i A', strtotime($hti));
                return response()->json([
                    "employee" => $employee,
                    "gender" => $gender,
                    "civilstatus" => $civilstatus,
                    "error" => "You already Time In today at ".$hti,
                ]);

            } else {
                $last_in_notimeout = table::attendance()->where([['idno', $idno],['timeout', NULL]]); 
                $count_notimeout = $last_in_notimeout->count();
    
                if($count_notimeout >= 1)
                {
                    $last_in_notimeout->update(['timeout' => 'no timeout']);
                    return response()->json([
                        "error" => "Updated clock-out from your last Clock In. Try again"
                    ]);

                } else {

                    $sched_in_time = table::schedules()->where([['idno', $idno], ['archive', 0]])->value('intime');
                    
                    $sched_clock_in_time_24h = date("H.i", strtotime($sched_in_time));
                    $time_in_24h = date("H.i", strtotime($time));
                    if ($time_in_24h <= $sched_clock_in_time_24h) {
                        $status_in = 'In Time';
                        $minutes_late = NULL;
                    } else {
                        $status_in = 'Late Arrival';
                        $sched_in_time = $sched_in_time; 
                        $now_time= Carbon::createFromFormat("h:i:s A", $time); 
                        
                        $minutes_late = $now_time->diffInMinutes($sched_in_time);
                        
                    }
                    
                    if($clock_comment == 1 && $comment != NULL) {
                        table::attendance()->insert([
                            [
                                'idno' => $idno,
                                'reference' => $employee_id,
                                'date' => $date,
                                'employee' => $employee,
                                'timein' => $date." ".$time,
                                'status_timein' => $status_in,
                                'ipin' => $ip,
                                'comment' => $comment,
                                'minutes_late' => $minutes_late,
                                'schedule_id' => $schedule_id,
                                
                            ],
                        ]);
                    } else {
                        table::attendance()->insert([
                            [
                                'idno' => $idno,
                                'reference' => $employee_id,
                                'date' => $date,
                                'employee' => $employee,
                                'timein' => $date." ".$time,
                                'status_timein' => $status_in,
                                'ipin' => $ip,
                                'minutes_late' => $minutes_late,
                                'schedule_id' => $schedule_id,
                            ],
                        ]);
                    }

                    return response()->json([
                        "type" => $type,
                        "time" => $time,
                        "date" => $date,
                        "lastname" => $lastname,
                        "firstname" => $firstname,
                        "mi" => $mi,
                        "gender" => $gender,
                        "civilstatus" => $civilstatus,
                        'totalhours' => NULL,
                    ]);
                }
            }
        }
  
        if ($type == 'Time Out') {
            $timeIN = table::attendance()->where([['idno', $idno], ['timeout', NULL], ['schedule_id', $schedule_id]])->value('timein');
            $clockInDate = table::attendance()->where([['idno', $idno],['timeout', NULL]])->value('date');
            

            $hasout = table::attendance()->where([['idno', $idno],['date', $date], ['schedule_id', $schedule_id]])->value('timeout');
            
            $timeOUT = date("Y-m-d h:i:s A", strtotime($date." ".$time));

            if($timeIN == NULL) {
                return response()->json([
                    "error" => "Please Clock In before Clocking Out."
                ]);
            } 

            if ($hasout != NULL) {
                $hto = table::attendance()->where([['idno', $idno],['date', $date]])->value('timeout');
                $hto = date('h:i A', strtotime($hto));
                return response()->json([
                    "employee" => $employee,
                    "gender" => $gender,
                    "civilstatus" => $civilstatus,
                    "error" => "You already Time Out today at ".$hto,
                ]);

            } else {

                $sched_out_time = table::schedules()->where([['idno', $idno],['id', $schedule_id], ['archive', 0]])->value('outime');
                
                    $sched_clock_out_time_24h = date("H.i", strtotime($sched_out_time));
                    $time_out_24h = date("H.i", strtotime($timeOUT));
                    if($time_out_24h >= $sched_clock_out_time_24h) {
                        $status_out = 'On Time';
                        $minutes_early = NULL;
                    } else {

                        $status_out = 'Early Departure';
                        $now_time = Carbon::createFromFormat("h:i:s A", $time);
                        $minutes_early = $now_time->diffInMinutes($sched_out_time) + 1;
                    }
                

                $time1 = Carbon::createFromFormat("Y-m-d h:i:s A", $timeIN); 
                $time2 = Carbon::createFromFormat("Y-m-d h:i:s A", $timeOUT); 
                $th = $time1->diffInHours($time2);
                $tm = floor(($time1->diffInMinutes($time2) - (60 * $th)));
                $totalhour = $th.".".$tm;

                table::attendance()->where([['idno', $idno],['date', $clockInDate]])->update(array(
                    'timeout' => $timeOUT,
                    'totalhours' => $totalhour,
                    'status_timeout' => $status_out,
                    'ipout' => $ip,
                    'minutes_early' => $minutes_early)
                );
                
                return response()->json([
                    "type" => $type,
                    "time" => $time,
                    "date" => $date,
                    "lastname" => $lastname,
                    "firstname" => $firstname,
                    "mi" => $mi,
                    "gender" => $gender,
                    "civilstatus" => $civilstatus
                ]);
            }
        }
    }
}
