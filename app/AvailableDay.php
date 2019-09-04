<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AvailableDay extends Model
{
    protected $fillable = [
        'day', 'from','to', 'fannie_id' 
    ];
}
 