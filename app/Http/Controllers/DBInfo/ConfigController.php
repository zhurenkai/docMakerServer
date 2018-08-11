<?php

namespace App\Http\Controllers\DBInfo;

use App\Model\DatabaseConfig;
use App\Model\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfigController extends Controller
{
    public function index(Request $request){
        $project_id = $request->get('project_id');
        if(!$project_id){
            return response()->error(2003);
        }
        $db_config = DatabaseConfig::where('project_id',$project_id)->first();
        return response()->success($db_config);
    }

    public function store(Request $request){
        $data = $request->all();
        $user_id =  auth()->id();
        $username =  auth()->user()->name;;
        $data['creator_id'] = $user_id;
        $data['creator_name'] = $username;
        $data['last_updater_name'] = $username;
        $data['last_updater_id'] = $user_id;
        $db_config = DatabaseConfig::create($data);
        if($db_config){
            return response()->success();
        }else{
            return response()->error(3004);
        }
    }

    public function show(){}

    public function update($id,Request $request){
        $db_config = DatabaseConfig::find($id);
        if(!$db_config){
            return response()->error(1004);
        }
        $data = $request->all();
        $res = $db_config->update($data);
        if ($res) {
            return response()->success();
        }else{
            return response()->error(6002);
        }
    }
}
