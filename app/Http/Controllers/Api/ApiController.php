<?php

namespace App\Http\Controllers\Api;

use App\Model\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;;

class ApiController extends Controller
{
    public function index()
    {
        return 1;
    }

    public function store(Request $request)
    {
        $name = $request->get('name');
        $module_id = $request->get('module_id');
        $user_id = auth()->id();
        $description = $request->get('description');
        $search = compact('name','module_id','user_id');
        if(Api::where($search)->first()) {
            return response()->error(1003);
        }
        $search['description'] = $description;
        $res = Api::create($search);
        if(!$res){
            return response()->error(1004);
        }
        return response()->success($res);
    }

    public function destroy($id)
    {
        $api = Api::find($id);
        if (!$api) {
            return response()->error(1001);
        }else{
            $res = $api->delete();
            if ($res) {
                return response()->success($res);
            }else{
                return response()->error(1002);
            }
        }

    }
}
