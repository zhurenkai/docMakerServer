<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['name','description','user_id','project_id'];

    public function apis()
    {
        return $this->hasMany('App\Model\Api');
    }
}
