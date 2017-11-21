<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class KeyStatement extends Model
{
    protected $fillable = ['key','statement','type','project_id','user_id'];
}
