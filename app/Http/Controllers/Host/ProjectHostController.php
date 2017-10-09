<?php

namespace App\Http\Controllers\Host;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Host;
class ProjectHostController extends Controller
{
    public function show($project_id)
    {
        $hosts = Host::where('project_id',$project_id)->get();
        return response()->success($hosts);
    }
}
