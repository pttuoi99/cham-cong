<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Device_token extends Model
{
    protected $table = 'tbl_device_token';
    protected $fillable = ['id','reference','token',];

    public $timestamps = false;
}
