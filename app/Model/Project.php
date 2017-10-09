<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function modules()
    {
        return $this->hasMany('App\Model\Module');
    }
}
