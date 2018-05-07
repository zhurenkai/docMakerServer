<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    protected $fillable = ['name','project_id','module_id','user_id','description'];

    public function params(){
        return $this->hasMany('App\Model\Param');
    }

    public function module(){
        return $this->belongsTo('App\Model\Module');
    }

    public function markdown(){
        return $this->hasOne('App\Model\Markdown');
    }
}
