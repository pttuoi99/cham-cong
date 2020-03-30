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
use Auth;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class SettingsController extends Controller
{
    public function index(Request $request) 
    { 
        if (permission::permitted('settings')=='fail'){ return redirect()->route('denied'); }
        $language = $request->user()->language;
        $data = table::settings()->where('id', 1)->first();
        $ip = $request->ip();
        if ($data !== null) 
        {
            $ss = json_decode($data->db_object);
            if(!isset($ss->activated_at)) {
                $s = "1";
            } else {
                $s = $ss;
            }
        } else {
            $s = "1";
        }
        $s = "1";
		$ss = "1";
    	return view('admin.settings', compact('language', 'data', 'ip', 's'));
    }

    public function update(Request $request) 
    {
        if (permission::permitted('settings-update')=='fail'){ return redirect()->route('denied'); }
        
        $country = $request->country;
        $timezone = $request->timezone;
        $clock_comment = $request->clock_comment;
        $iprestriction = $request->iprestriction;
        
        
        if ($country == null && $timezone == null && $clock_comment == null && $iprestriction == null) {
            return redirect('settings');
        }

        if ($timezone == null) {
            return redirect('settings')->with('error', 'Please select your timezone.');
        } else {
            $t = table::settings()->where('id', 1)->value('timezone');
            $path = base_path('.env');
            if(file_exists($path)) {
                file_put_contents($path, str_replace('APP_TIMEZONE='.$t, 'APP_TIMEZONE='.$timezone, file_get_contents($path)));
            }
        }

        table::settings()
        ->where('id', 1)
        ->update([
                'country' => $country,
                'timezone' => $timezone,
                'clock_comment' => $clock_comment,
                'iprestriction' => $iprestriction,
        ]);
        
        return redirect('settings')->with('success', 'Settings has been updated. Please try re-login for the new settings to take effect.');
    }

    public function languages(Request $request) {
        if (permission::permitted('settings-update')=='fail'){ return redirect()->route('denied'); }
        $user_id = $request->user()->id;
        $language = $request->language;
        $locale = $language;
        session()->put('locale', $locale); //setup language to session

        table::users()
        ->where('id', $user_id)
        ->update([
                'language' => $language,
        ]);
        
        return redirect('/');
    }

    public function appInfo() 
    {

    }

    public function reverse(Request $request) 
    {
        $id = $request->id;
        $auth_id = \Auth::user()->id;

        if(!isset($id)) 
        {
            return response()->json([
                "error" => "Invalid request.",
            ]);
        }

        if(isset($id)) 
        {
            if($id != $auth_id) 
            {
                return response()->json([
                    "error" => "Invalid request. Wrong ID.",
                ]);
            } else {
                $data = table::settings()->where('id', 1)->value('db_object');
  
                table::settings()->where('id', 1)
                ->update([
                    "db_object" => "",
                ]);

                return response()->json([
                    "success" => "Your app is deactivated.",
                    "data" => $data,
                ]);
            }
        }
    }
}
