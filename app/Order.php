<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_name','user_mobile','city','area','let','lang','container_name_ar','container_name_en','container_size','no_container','notes','status','user_id','center_id','driver_id','container_id','price','total','city_id','area_id'
    ];
    public function fannie()
    {
        return $this->belongsTo('App\User','fannie_id')->with('technician');
    }
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function service()
    {
        return $this->belongsTo('App\Service','service_id');
    }
    
}
																
