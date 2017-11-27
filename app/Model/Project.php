<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name','description','user_id'];
    public function modules()
    {
        return $this->hasMany('App\Model\Module');
    }
}
