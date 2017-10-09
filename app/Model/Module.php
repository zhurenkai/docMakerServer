<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public function apis()
    {
        return $this->hasMany('App\Model\Api');
    }
}
