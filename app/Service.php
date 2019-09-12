<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
class Service extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $fillable = [
        'name_ar','name_en','image','status'
    ];

    

    public function fannies()
    {
        return $this->hasMany('App\Technician','service_id')->with('user');
    }
    
}
