<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class People_leaves extends Model
{
    protected $table 	= 'tbl_people_leaves';

    protected $fillable = ['reference','idno','employee','typeid','type','leavefrom','leaveto','returndate','reason','status','comment','archived',
	];


    public $timestamps = false;
}
