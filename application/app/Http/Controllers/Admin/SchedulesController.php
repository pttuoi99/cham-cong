<?php
/*
* Smart Timesheet: Time and Attendance Management System
* Email: official.smarttimesheet@gmail.com
* Version: 1.0
* Author: Brian Luna
* Copyright 2018 Brian Luna
* Website: https://github.com/brianluna/smarttimesheet
*/
namespace App\Http\Controllers\admin;
use DB;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class SchedulesController extends Controller
{
    public function index() 
    {
        if (permission::permitted('schedules')=='fail'){ return redirect()->route('denied'); }
        
        $department = table::department()->get();
        $employee = table::people()->get();
        $schedules = table::schedules()->get();
    
        return view('admin.schedules', compact('department', 'employee', 'schedules'));
    }

    public function select_department(Request $request){
        if (permission::permitted('schedules')=='fail'){ return redirect()->route('denied'); }
        $department = $request->department;
        $people = table::people()->join('tbl_company_data', 'tbl_people.id', '=', 'tbl_company_data.reference')->where("department", "$department")->get();
        
        if(count($people) > 0){
            return response()->json([
                "people" => $people,
            ]);
        }
        else{
            if($department == "all"){
                return response()->json(["all" => "all"]);
            }else{
                return response()->json(["error" => "no employees exists!"]);
            }
            
        }

    }

    public function add(Request $request) 
    {
        if (permission::permitted('schedules-add')=='fail'){ return redirect()->route('denied'); }
        if($request->sh !== 2){return redirect()->route('schedule');}

        $department = $request->department;
        $arr_id = $request->employee;
		$intime = $request->intime;
		$outime = $request->outime;
		$datefrom = $request->datefrom;
		$dateto = $request->dateto;
		$hours = $request->hours;
        $restday = ($request->restday != null) ? implode(', ', $request->restday) : null ;


        if ( $intime == '' || $outime == '') {
        	return redirect('schedules')->with('error', 'Whoops! Please fill the form completely.');
        } 

        # Save All Emoloyees
        if($department == "all"){
            $people = table::people()->get();

            if(count($people) == 0) return redirect('schedules')->with('error', 'Whoops! no employees exists.');
            
            foreach ($people as $key => $item) {
                $id = $item->id;
                $emp_id = table::companydata()->where('reference', $id)->value('idno');
                $lastname = $item->lastname;
                $firstname = $item->firstname;
                $employee = $lastname.", ".$firstname;

                table::schedules()->where('id', $id)->insert([
                    'reference' => $id,
                    'idno' => $emp_id,
                    'employee' => $employee,
                    'intime' => $intime,
                    'outime' => $outime,
                    'datefrom' => $datefrom,
                    'dateto' => $dateto,
                    'hours' => $hours,
                    'restday' => $restday,
                    'archive' => '0',
                ]);
                
            }
            
            return redirect('schedules')->with('success', 'New Schedule Updated!');
        }

        
        # Save The Selected Employees
        if(empty($arr_id)) return redirect('schedules')->with('error', 'Whoops! no employees exists.');

        foreach ($arr_id as $key => $id) {
                
                $emp_id = table::companydata()->where('reference', $id)->value('idno');
                $people = table::people()->where('id', $id)->first();
                $lastname = $people->lastname;
                $firstname = $people->firstname;
                $employee = $lastname.", ".$firstname;
                
                table::schedules()->where('id', $id)->insert([
                    'reference' => $id,
                    'idno' => $emp_id,
                    'employee' => $employee,
                    'intime' => $intime,
                    'outime' => $outime,
                    'datefrom' => $datefrom,
                    'dateto' => $dateto,
                    'hours' => $hours,
                    'restday' => $restday,
                    'archive' => '0',
                ]);
            
        }
        
        
    	return redirect('schedules')->with('success', 'New Schedule Updated!');
	}

    public function edit($id, Request $request) 
    {
        if (permission::permitted('schedules-edit')=='fail'){ return redirect()->route('denied'); }

        $s = table::schedules()->where('id', $id)->first();
        $r = explode(', ', $s->restday);
        
        return view('admin.edits.edit-schedule', compact('s','r'));
    }

    public function update(Request $request) 
    {
        if (permission::permitted('schedules-edit')=='fail'){ return redirect()->route('denied'); }
        if($request->sh !== 2){return redirect()->route('schedule');}

        $id = $request->id;
        $intime = $request->intime;
        $outime = $request->outime;
        $datefrom = $request->datefrom; 
        $dateto = $request->dateto; 
        $hours = $request->hours;
        $restday = implode(', ', $request->restday);

        if ($id == null || $intime == null || $outime == null || $datefrom == null || $dateto == null || $restday == null) {
            return redirect('schedules')->with('error', 'Whoops! Please fill the form completely.');
        }

        table::schedules()
        ->where('id', $id)
        ->update([
                'intime' => $intime,
                'outime' => $outime,
                'datefrom' => $datefrom,
                'dateto' => $dateto,
                'hours' => $hours,
                'restday' => $restday,
        ]);

        return redirect('schedules')->with('success', 'Schedule has been updated!');
    }

    public function delete($id, Request $request) 
    {
        if (permission::permitted('schedules-delete')=='fail'){ return redirect()->route('denied'); }
        if($request->sh !== 2){return redirect()->route('schedule');}

        table::schedules()->where('id', $id)->delete();

        return redirect('schedules')->with('success', 'Deleted!');
    }

    public function archive($id, Request $request)
    {
		if (permission::permitted('schedules-archive')=='fail'){ return redirect()->route('denied'); }
        if($request->sh !== 2){return redirect()->route('schedule');}
        
		$id = $request->id;
		table::schedules()->where('id', $id)->update(['archive' => '1']);

    	return redirect('schedules')->with('success','Schedule has been archived.');
   	}

}