<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'evaluator_from','evaluator_to','rate','contact_rate','time_rate','work_rate','cost_rate','general_character','date','notes'
    ];

    									

}