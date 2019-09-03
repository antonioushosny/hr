<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Country extends Model
{
    use Notifiable;

    protected $fillable = [
        'name_ar','name_en','image','status'
    ];

    

    public function cities()
    {
        return $this->hasMany('App\City','country_id')->with('areas');
    }
    
}
