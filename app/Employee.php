<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
 use App\Notifications\MailResetPasswordNotification;
// use Illuminate\Database\Eloquent\SoftDeletes;
class Employee extends Authenticatable
{
    use Notifiable;
    // use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password','mobile','national_id','mac_address','net_salary','cross_salary','insurance','annual_vacations','accidental_vacations','image','device_token','status','type','department_id','lang'
    ];
     	 	 	 	 	 	 	 	 	 	 	 	 	 	 	 	
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordNotification($token));
    }
    protected $hidden = [
        'password',
        //  'remember_token',
    ];
    public function generateToken()
    {
        $this->remember_token = str_random(60);
        $this->save();
        return $this->api_token;
    }

    public function department()
    {
        return $this->belongsTo('App\Department','department_id');
    }
    
}
