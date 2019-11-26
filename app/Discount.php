<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Discount extends Model
{
    use Notifiable;

    protected $fillable = [
        'amount','reason','employee_id'
    ];
     	 	
    public function employee()
    {
        return $this->belongsTo('App\Employee','employee_id');
    }
}
