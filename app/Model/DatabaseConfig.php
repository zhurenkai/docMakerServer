<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DatabaseConfig extends Model
{
    protected $fillable = ['project_id','host','port','username','password','databases','creator_name','creator_id','last_updater_name','last_updater_id','databases'];
}
