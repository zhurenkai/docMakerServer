<?php

namespace App\Http\Controllers\Project;

use App\Model\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserProjectController extends Controller
{
    public function index()
    {
        $projects = $this->myProject();
        foreach ($projects as $v) {
            if ($v->modules) {
                foreach ($v->modules as $module) {
                    $module->apis;
                }
            }
        }
        return response()->success($projects);
    }

    private function myProject()
    {
        $user_id = auth()->id();
        return Project::where('user_id', $user_id)->get();
    }

    public function store(Request $request)
    {
        $user_id = auth()->id();
        $name = $request->get('name');
        $description = $request->get('description');
        $data = compact('user_id', 'name', 'description');
        if (Project::where(['name' => $name, 'user_id' => $user_id])->first()) {
            return response()->error(1003);
        }
        $res = Project::create($data);
        if (!$res) {
            return response()->error(1004);
        }
        return response()->success($res);

    }
}
