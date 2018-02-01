<?php

namespace App\Http\Controllers\Module;

use App\Model\Module;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ModuleController extends Controller
{
    public function store(Request $request){
        $user_id = auth()->id();
        $name = $request->get('name');
        $description = $request->get('description');
        $project_id = $request->get('project_id');
        $query = compact('user_id','name','project_id');
        if(Module::where($query)->first()){
            return response()->error(5003);
        }
        $query['description'] = $description;
        if($res = Module::create($query)){
            return  response()->success($res);
        }
        return response()->error(3004);

    }
}
