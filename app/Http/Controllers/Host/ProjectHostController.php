<?php

namespace App\Http\Controllers\Host;

use App\Model\Host;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\Host\Host as HostRepo;

class ProjectHostController extends Controller
{
//    public function show($project_id)
//    {
//        $hosts = Host::where('project_id',$project_id)->get();
//        return response()->success($hosts);
//    }
    private $host;

    public function __construct(HostRepo $host)
    {
        $this->host = $host;
    }

    public function index()
    {
        $user_id = auth()->id();
        $project_hosts = $this->host->getUserHosts($user_id);
        return response()->success($project_hosts);
    }

    public function update($id, Request $request)
    {
        $project = $this->host->show($id);
        $hosts = $request->get('hosts');
        if (!$project) {
            return response()->error(1002);
        }
        $old_ids = Host::where('project_id',$id)->get()->pluck('id')->toArray();
        list($rest,$new) = array_separate($hosts,function($v){
            return isset($v['id']);
        });
        $rest_ids = array_column($rest,'id');
        $delete_ids = array_diff($old_ids,$rest_ids);
        Host::destroy($delete_ids);
        if (!empty($rest)) {
           $this->host->updateMany($rest,'id');
        }
        if(!empty($new)){
            $this->host->bindTimeInfo($new);
            Host::insert($new);
        }
        return response()->success('success');
    }
}
