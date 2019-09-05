<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
       'fannie_id','user_id','service_id','lat','lng','notes','date','time','address','status','rejected_reason'
    ];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function fannie()
    {
        return $this->belongsTo('App\User','fannie_id')->with('technician');
    }
    public function services()
    {
        return $this->belongsTo('App\Service','service_id');
    }
    public function provider()
    {
        return $this->belongsTo('App\User','provider_id');
    }
    public function center()
    {
        return $this->belongsTo('App\User','center_id');
    }
    public function city()
    {
        return $this->belongsTo('App\City','city_id');
    }
    public function area()
    {
        return $this->belongsTo('App\Area','area_id');
    }
   
    public function driver()
    {
        return $this->belongsTo('App\User','driver_id');
    }
    public function container()
    {
        return $this->belongsTo('App\Container','container_id');
    } 
    public function centers()
    {
        return $this->hasMany('App\OrderCenter','order_id')->with('center');
    }
    public function drivers()
    {
        return $this->hasMany('App\OrderDriver','order_id')->with('driver');
    }
}
																
