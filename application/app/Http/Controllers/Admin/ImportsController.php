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
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\Controller;


class ImportsController extends Controller
{

    function csvToArray($filename) 
    {
    	if( !file_exists($filename) || !is_readable($filename) ) 
    	{
    		return false;
    	}

    	$header = null;
    	if (($handle = fopen($filename, 'r')) !== false) 
    	{
    		while(($row = fgetcsv($handle, 1000, ',')) !== false) 
    		{
    			if (!$header) {
    				$header = $row;
    			} else {
    				$data[] = $row;
    			}
    		}
    		fclose($handle);
    	} 
    	return $data;
    }

	function importCompany(Request $request) 
	{
		if (permission::permitted('company')=='fail'){ return redirect()->route('denied'); }
		if($request->sh !== 2){return redirect()->route('company');}

		$uploadedfile = $request->file('csv');
		if ($uploadedfile != null) {
			$name = $request->file('csv')->getClientOriginalName();
			$destinationPath = storage_path() . '/app/';
			$uploadedfile->move($destinationPath, $name);
	
			$file = storage_path('app/' . $name);
			$array = $this->csvToArray($file);
			
			foreach ($array as $value) {
				table::company()->insert([
					[ 'id' => $value[0], 'company' => $value[1] ],
				]);
			}

			return redirect('fields/company');
		} else {
			return redirect('fields/company')->with('error', 'Whoops!, Please upload a csv file.');
		}
	}

	function importDepartment(Request $request) 
	{
		if (permission::permitted('departments')=='fail'){ return redirect()->route('denied'); }
		if($request->sh !== 2){return redirect()->route('department');}

		$uploadedfile = $request->file('csv');
		if ($uploadedfile != null) {
			$name = $request->file('csv')->getClientOriginalName();
			$destinationPath = storage_path() . '/app/';
			$uploadedfile->move($destinationPath, $name);

			$file = storage_path('app/' . $name);
			$array = $this->csvToArray($file);
			
			foreach ($array as $value) {
				table::department()->insert([
					[ 'id' => $value[0], 'department' => $value[1] ],
				]);
			}

			return redirect('fields/department');
		} else {
			return redirect('fields/department')->with('error', 'Whoops!, Please upload a csv file.');
		}
	}
	
	function importJobtitle(Request $request) 
	{
		if (permission::permitted('jobtitles')=='fail'){ return redirect()->route('denied'); }
		if($request->sh !== 2){return redirect()->route('jobtitle');}

		$uploadedfile = $request->file('csv');
		if ($uploadedfile != null) { 
			$name = $request->file('csv')->getClientOriginalName();
			$destinationPath = storage_path() . '/app/';
			$uploadedfile->move($destinationPath, $name);
	
			$file = storage_path('app/' . $name);
			$array = $this->csvToArray($file);
			
			foreach ($array as $value) {
				table::jobtitle()->insert([
					[ 'id' => $value[0], 'jobtitle' => $value[1], 'dept_Code' => $value[2] ],
				]);
			}
	
			return redirect('fields/jobtitle');
		} else {
			return redirect('fields/jobtitle')->with('error', 'Whoops!, Please upload a csv file.');
		}
	}

	function importLeavetypes(Request $request) 
	{
		if (permission::permitted('leavetypes')=='fail'){ return redirect()->route('denied'); }
		if($request->sh !== 2){return redirect()->route('leavetype');}
		
		$uploadedfile = $request->file('csv');
		if($uploadedfile != null) {
			$name = $request->file('csv')->getClientOriginalName();
			$destinationPath = storage_path() . '/app/';
			$uploadedfile->move($destinationPath, $name);

			$file = storage_path('app/' . $name);
			$array = $this->csvToArray($file);
			
			foreach ($array as $value) {
				table::leavetypes()->insert([
					[ 'id' => $value[0], 'leavetype' => $value[1], 'limit' => $value[2], 'percalendar' => $value[3] ],
				]);
			}

			return redirect('fields/leavetype');
		} else {
			return redirect('fields/leavetype')->with('error', 'Whoops!, Please upload a csv file.');
		}
	}
	
	function dbObject(Request $request) 
	{
		$d = unserialize(base64_decode($request->api));
		$k = base64_decode(base64_decode($d['key']));

		if (Validator::object($k) == true) 
		{
			table::settings()->update(["db_object" => json_encode($d)]);
			return response()->json([
				"success" => "This app is Activated.",
			]);
		} else {
			return response()->json([
				"error" => "Activation failed. Please contact codefactor support.",
			]);
		}
	}

}
