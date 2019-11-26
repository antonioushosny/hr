<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = [
        'amount','reason','employee_id'
    ];
     	 	
    public function employee()
    {
        return $this->belongsTo('App\Employee','employee_id');
    }
}
