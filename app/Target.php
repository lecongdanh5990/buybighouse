<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Target extends Model{
    
    protected $fillable = ['name','purpose_id','complete','ontime','frequency',
        'last_completed_day',
        'time_to_complete','time_has_completed',
        'last_date_to_do','repeat'];
    public function purpose(){
        return $this->belongsTo('App\Purpose');
    }
    
}
