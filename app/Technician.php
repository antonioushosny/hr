<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\MailResetPasswordNotification;
class Technician extends Authenticatable
{
    use HasRoles;
    use Notifiable;

    protected $fillable = [
        'renewal_date', 'available','brief', 'user_id','service_id','country_id','city_id','area_id','nationality_id','identity_photo'
    ];
 
    
    public function country()
    {
        return $this->belongsTo('App\Country','country_id');
    }
    public function city()
    {
        return $this->belongsTo('App\City','city_id');
    }             
    public function area()
    {
        return $this->belongsTo('App\Area','area_id');
    }                     
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function service()
    {
        return $this->belongsTo('App\Service', 'service_id');
    }

    public function nationality()
    {
        return $this->belongsTo('App\Nationality', 'nationality_id');
    }
 
}

