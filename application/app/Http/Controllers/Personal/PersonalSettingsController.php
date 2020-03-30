<?php
/*
* Smart Timesheet: Time and Attendance Management System
* Email: official.smarttimesheet@gmail.com
* Version: 1.0
* Author: Brian Luna
* Copyright 2018 Brian Luna
* Website: https://github.com/brianluna/smarttimesheet
*/
namespace App\Http\Controllers\personal;
use DB;
use App\Classes\table;
use App\Classes\permission;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class PersonalSettingsController extends Controller
{
    public function index(Request $request) 
    {
        $language = $request->user()->language;
        return view('personal.personal-settings-view', compact('language'));
    }

    public function languages(Request $request) {
        $user_id = $request->user()->id;
        $language = $request->language;
        $locale = $language;
        session()->put('locale', $locale); //setup language to session

        table::users()
        ->where('id', $user_id)
        ->update([
                'language' => $language,
        ]);
        
        return redirect('personal/dashboard');
    }
}

