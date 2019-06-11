<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    protected $fillable = ['name','mission_id','priority','is_complete'];
    public function mission()
    {
        return $this->belongsTo('App\Mission');
    }
}
