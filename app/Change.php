<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Change extends Model
{
    protected $fillable = [
        'title','reason','employee_id','department_id'
    ];
     	 	
    public function employee()
    {
        return $this->belongsTo('App\Employee','employee_id');
    }
    public function department()
    {
        return $this->belongsTo('App\Department','department_id');
    }
}
