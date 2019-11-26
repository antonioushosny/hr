<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
 class Task extends Model
{
    use Notifiable;
     protected $fillable = [
        'date','time','project_name','status','employee_id','title'
    ];
     
    public function employee()
    {
        return $this->belongsTo('App\Employee','employee_id');
    }
    
}
