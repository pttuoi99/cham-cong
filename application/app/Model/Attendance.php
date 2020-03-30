<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'tbl_people_attendance';
    protected $fillable = [
        'reference','idno','date', 'employee', 'timein', 'timeout', 'totalhours','status_timein','status_timeout','reason','comment',
    ];

    public $timestamps = false;
}


    
