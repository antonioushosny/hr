<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mac extends Model
{
    protected $fillable = [
        'mac_address','reason','employee_id'
    ];
     	 	
    public function employee()
    {
        return $this->belongsTo('App\Employee','employee_id');
    }
    
}
