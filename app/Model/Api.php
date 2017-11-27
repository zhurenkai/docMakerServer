<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    protected $fillable = ['name','project_id','module_id','user_id','description'];
}
