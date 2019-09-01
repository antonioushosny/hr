<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionType extends Model
{
    //

     protected $fillable = [
        'name_ar','name_en','no_month','status','cost'
    ];
}
