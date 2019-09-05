<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user_id','fannie_id' 
    ];
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }									

    public function fannie()
    {
        return $this->belongsTo('App\User', 'fannie_id')->with('technician');
    }
}
