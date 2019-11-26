<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Attendance extends Model
{
    use Notifiable;

    protected $fillable = [
        'date','check_in','check_out','employee_id'
    ];

    public function employee()
    {
        return $this->belongsTo('App\Employee','employee_id');
    }
}
	 	 	 	 