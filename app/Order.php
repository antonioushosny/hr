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
 
    public function service()
    {
        return $this->belongsTo('App\Service','service_id');
    }
    
}
																
