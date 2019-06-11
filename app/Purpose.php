<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purpose extends Model{
    protected $fillable = ['name','startday','endday','realendday','iscomplete','user_id'];
    public function targets(){
        return $this->hasMany('App\Target');
    }
    public function statuses(){
        return $this->belongsToMany('App\Status');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
}
