<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Service extends Model
{
    use Notifiable;

    protected $fillable = [
        'name_ar','name_en','image','status'
    ];

    

    public function fannies()
    {
        return $this->hasMany('App\fannie','service_id');
    }
    
}
