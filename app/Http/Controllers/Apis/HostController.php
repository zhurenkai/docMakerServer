<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Host;
class HostController extends Controller
{
    public function getHostsByProjectId(Request $request)
    {
        $project_id = $request->get('project_id');
        $hosts = Host::where('project_id',$project_id)->get();
        return response()->success($hosts);
    }
}
