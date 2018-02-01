<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Host extends Model
{
    protected $fillable = ['name','project_id','description','is_default'];
}
