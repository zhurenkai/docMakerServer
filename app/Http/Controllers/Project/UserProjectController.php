<?php

namespace App\Http\Controllers\Project;

use App\Model\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserProjectController extends Controller
{
    public function index()
    {
        $user_id = auth()->id();
        $projects = Project::where('user_id', $user_id)->get();
        return response()->success($projects);
    }


    public function projectsWithApi()
    {
        $user_id = auth()->id();
        $projects = Project::where('user_id', $user_id)
            ->with(['modules'=>function($query){
            $query->with('apis');
        }])
            ->get();
        return response()->success($projects);
    }

    public function store(Request $request)
    {
        $user_id = auth()->id();
        $name = $request->get('name');
        $description = $request->get('description');
        $data = compact('user_id', 'name', 'description');
        if (Project::where(['name' => $name, 'user_id' => $user_id])->first()) {
            return response()->error(5003);
        }
        $res = Project::create($data);
        if (!$res) {
            return response()->error(3004);
        }
        return response()->success($res);

    }
}
