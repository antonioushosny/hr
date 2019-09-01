<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\MailResetPasswordNotification;
class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;

    protected $fillable = [
        'code', 'name','email', 'password','mobile','address','city','area','lat','lng','image','device_token','role','status','lang','type',
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
    public function country()
    {
        return $this->belongsTo('App\Country','country_id');
    }
    public function City()
    {
        return $this->belongsTo('App\City','city_id');
    }             
    public function Area()
    {
        return $this->belongsTo('App\Area','area_id');
    }                            
    public function usersorders()
    {
        return $this->hasMany('App\Order', 'user_id');
    }
    public function fannieorders()
    {
        return $this->hasMany('App\Order', 'fannie_id');
    }
 
}
