<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    protected $fillable = [
        'title','from','to','days','type','notes','status','employee_id'
    ];
    
    public function employee()
    {
        return $this->belongsTo('App\Employee','employee_id');
    }
}
