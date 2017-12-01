<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Param extends Model
{

    protected $casts = [
        'required' => 'boolean',
        'is_use' => 'boolean'
    ];
    protected $fillable = ['api_id','name','value','type','required','is_use','description'];

}
