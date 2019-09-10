<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    //
    protected $fillable = [
        'date','image','fannie_id','subscription_id'
    ];
    public function subscription_type()
    {
        return $this->belongsTo('App\SubscriptionType','subscription_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User','fannie_id')->with('technician');
    } 
}


