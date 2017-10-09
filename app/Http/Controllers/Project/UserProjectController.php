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
        foreach ($projects as $v){
            if($v->modules){
                foreach ($v->modules as $module) {
                    $module->apis;
                }
            }

        }
        return response()->success($projects);
    }

    private function myProject(){
        $user_id = auth()->id();
        return Project::where('user_id',$user_id)->get();
    }
}
