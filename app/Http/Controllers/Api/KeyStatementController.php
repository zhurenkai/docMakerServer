<?php


namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Model\KeyStatement;
use Illuminate\Support\Facades\Auth;

class KeyStatementController
{
    public function index(Request $request)
    {
        if (!$key = $request->get('key')) {
            return response('缺失key');
        }
        if (!$project_id = $request->get('project_id')) {
            return response('缺失project_id');
        }
        // 本人曾用或者本项目其他人曾用
        $record = KeyStatement::where(['key'=>$key])->where(
            function($queryBuilder)use($project_id){
                return $queryBuilder->where('user_id',Auth::id())->orWhere('project_id',$project_id);
            })->orderBy('weight','desc ')->get();
        return response()->success($record);
    }

    public function store(Request $request)
    {
        if (!$key = $request->get('key')) {
            return response('缺失key');
        }
        if (!$project_id = $request->get('project_id')) {
            return response('缺少project_id');
        }
        if (!$project_id = $request->get('statement')) {
            return response('缺少描述');
        }
        if (!$project_id = $request->get('type')) {
            return response('缺少类型');
        }
        $data = compact(['key', 'project_id', 'statement', 'type']);
        return KeyStatement::create($data);
    }

    /**
     *
     * 从数据库批量导入
     *
     */
    public function storeMany(Request $request)
    {
        $list = $request->get('list');
       // return response()->success($list);
//        dd($list[0]);
        $res = KeyStatement::insert($list);
        return response()->success($res);
    }

}