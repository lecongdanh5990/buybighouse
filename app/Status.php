<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['content','tomato','rating','user_id'];
    
    public function purposes()
    {
        return $this->belongsToMany('App\Purpose');
    }
    
}
