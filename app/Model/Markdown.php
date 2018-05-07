<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Markdown extends Model
{
    protected $fillable = ['content','api_id','creator_id','last_updater_id','last_updater_name','creator_name'];
    public function api()
    {
        return $this->belongsTo('App\Model\Api');
    }
}
