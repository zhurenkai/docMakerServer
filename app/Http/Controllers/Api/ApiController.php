<?php

namespace App\Http\Controllers\Api;

use App\Model\Api;
use App\Model\Host;
use App\Model\Module;
use App\Model\Param;
use App\Repository\Api\Param as ParamRepo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    protected $param;
    public function __construct(ParamRepo $param)
    {
        $this->param = $param;
    }

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
            return response()->error(6003);
        }
        $search['description'] = $description;
        $res = Api::create($search);
        if(!$res){
            return response()->error(3004);
        }
        return response()->success($res);
    }

    public function destroy($id)
    {
        $api = Api::find($id);
        if (!$api) {
            return response()->error(1001);
        } else {
            $res = $api->delete();
            if ($res) {
                return response()->success($res);
            } else {
                return response()->error(4002);
            }
        }

    }

    public function show($id)
    {
        $api = Api::find($id);
        if (!$api) {
            return response()->error(1001);
        }
        $project_id = Module::find($api->module_id)->project_id;
        $hosts = Host::where('project_id', $project_id)->get()->all();
        array_walk($hosts,function(&$item){
            return $item->headers = json_decode($item->headers)?:[];
        });
        $api->hosts = $hosts;
        $api->params;
        $api->document = json_decode($api->document);
        return response()->success($api);

    }

    public function update(Request $request ,$id)
    {
         $api = Api::find($id);
         if(!$api){
             return response()->error(1001);
         }
         $path = $request->get('path');
         $method = $request->get('method');
         $params = $request->get('params');
         $host = $request->get('host');
         $json_input = $request->get('json_input');
         if(!$path){
             return response()->error(2001);
         }
        if(!$host){
            return response()->error(2002);
        }
         $api->path = $path;
         $api->method = $method;
         $api->json_input = $json_input;
         $api->host = $host;
         $api->save();
         list($update_params,$store_params) = array_separate($params,function($v){
             return isset($v['id']);
         });
         array_walk($update_params,function(&$value){
             $value['required'] = $value['required'] ? 1:0;
             $value['is_use'] = $value['is_use'] ? 1:0;
         });
         $current_params = $api->params()->pluck('id')->toArray();
         $old_params = array_column($update_params,'id');
         $delete_params = array_diff($current_params,$old_params);
         Param::destroy($delete_params);
         if(!empty($update_params)){
             $this->param->updateMany($update_params,'id');

         }
         if(!empty($store_params)){
             Param::insert($store_params);
         }
         return response()->success('success');
    }
}
