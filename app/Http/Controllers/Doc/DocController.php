<?php

namespace App\Http\Controllers\Doc;

use App\Model\Api;
use App\Model\KeyStatement;
use App\Model\Markdown;
use App\Model\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DocController extends Controller
{


    public function generate(Request $request)
    {
        $query_params = $request->get('queryParam');
        $form_data = $request->get('formParam');
        $project_id = $request->get('project_id');
        $json_params = $request->get('jsonParam');

        $doc['params'] = [];
        // TODO 写的什么狗屎，待优化
        foreach ($query_params as $index => $value) {
            $info = $this->getStatement($value['key'],$value['value']);
            $info['required'] = $value['required'];
            $doc['params'][] = $info;
        }

        foreach ($form_data as $index => $value) {
            $info = $this->getStatement($value['name'],$value['value']);
            $info['required'] = $value['required'];
            $doc['params'][] = $info;
        }
        if ($json_params) {
            $json_params = $this->jsonStatements($json_params);
            $doc['params'] += $json_params;
        }
        $response_data = $request->get('response');
        if (is_array($response_data)) {
            $doc['response'] = $this->jsonStatements($response_data);
        }
        return response()->success($doc);
    }


    /**
     * 递归查询返回参数中的说明
     * @param $response_data
     * @param array $res
     * @return array
     */
    private function jsonStatements($response_data, &$res = [])
    {

        foreach ($response_data as $k => $v) {

            // 用key过滤重复的参数
            if (!key_exists($k, $res) && !is_int($k)) {
                $res[$k] = $this->getStatement($k,$v);
            }
            // 数组的情况
            if (is_array($v)) {
                // 索引数组的并且是多维数组（如果数组里面的值类型不一样，就该去反思，写的什么鬼接口）
                if (is_array(end($v)) && array_keys($v) == array_keys(array_keys($v))) {
                    $this->jsonStatements($v[0], $res);
                } // 关联数组数组
                else {
                    $this->jsonStatements($v, $res);
                }
            }
        }
        return array_values($res);
    }

    private function getStatement($key,$value)
    {
        $info = KeyStatement::where('key', $key)
            ->orderByRaw('user_id=' . auth()->id() . ' desc')
//                等加上项目id再说
//                ->orderByRaw('project_id='.$project_id.' desc')
            ->orderByDesc('weight')
            ->get();
        $type = gettype($value);
        switch ($type){
            case 'array':
                if (array_keys($value) != array_keys(array_keys($value))){
                    $type = 'object';
                }
                break;
            case 'NULL':
                $type = 'string';
                break;
        }
        $ret = [
            'key'               => $key,
            'statement'         => '',
            'statement_options' => [],
            'type'              => $type,
            'required'          => ''
        ];
        if (!$info->isEmpty()) {
            $ret['statement'] = $info[0]->statement;
            $ret['statement_options'] = $info;
            $ret['type'] = $info[0]->type;
        }
        return $ret;
    }

    public function store(Request $request)
    {
        $params = $request->get('params');
        $response = $request->get('response');
        $project_id = $request->get('project_id', 1);
        $api_id = $request->get('api_id');
        $this->updateApi($api_id, $params, $response);
        $union = array_merge($params, $response);
        $keys = array_column($union, 'key');
        $union = array_combine($keys, $union);
        $user_id = auth()->id();
        // sql 结构 where aaa and ( (bbb and ccc) or (ddd and eee ))  查出用户这些键值对应的备注
        $exists_keys = KeyStatement::where(['user_id' => $user_id, 'project_id' => $project_id])
            ->where(function ($query) use ($union) {
                foreach ($union as $v) {
                    $query->orWhere(function ($query) use ($v) {
                        $query->Where(['key' => $v['key'], 'statement' => $v['statement'], 'type' => $v['type']]);
                    });

                }
            })
            ->get()->keyBy('key')->toArray();

        // 对比
        $not_exists = array_diff_key($union, $exists_keys);
        $insert = array_map(function ($v) use ($user_id, $project_id) {
            return [
                'key'        => $v['key'],
                'statement'  => $v['statement'],
                'type'       => $v['type'] ?? 'varchar',
                'user_id'    => $user_id,
                'project_id' => $project_id,
            ];
        }, $not_exists);
        $res = KeyStatement::insert($insert);
        if ($res) {
            return response()->success('success');
        }
        return response()->error('更新失败');
    }

    private function updateApi($api_id, $params, $response)
    {
        $api = Api::find($api_id);
        if (!$api) {
            return false;
        }
        $document = [
            'params'   => array_map(function ($v) {
                return [
                    'key'       => $v['key'],
                    'required'  => $v['required'],
                    'statement' => $v['statement'],
                    'type'      => $v['type'],
                ];
            }, $params),
            'response' => array_map(function ($v) {
                return [
                    'key'       => $v['key'],
                    'required'  => $v['required'],
                    'statement' => $v['statement'],
                    'type'      => $v['type'],
                ];
            }, $response)
        ];
        $api->document = json_encode($document);
        $api->save();
    }

    public function saveMarkDownDoc(Request $request)
    {
        $content = $request->get('content');
        $api_id = $request->get('api_id');
        $api = Api::find($api_id);
        if(!$api){
            return response()->error(1001);
        }
        $markdown = Markdown::where('api_id',$api_id)->first();
        $user_id = auth()->id();
        $user_name = auth()->user()->name;
        if(!$markdown){
            $markdown = new Markdown();
            $markdown->creator_id = $user_id;
            $markdown->creator_name = $user_name;
        }
        $markdown->api_id = $api_id;
        $markdown->last_updater_id = $user_id;
        $markdown->last_updater_name = $user_name;
        $markdown->content = $content;
        $markdown->save();
        $this->buildMarkdown($markdown);
        return response()->success();
    }

    public function buildMarkdown($markdown)
    {
        $config = 'site_name: MkLorum
pages:
    - 模块1: index.md
    - 模块2: aaa.md
theme: readthedocs
';
        $api = $markdown->api;
//        dd($api);
        $module = $api->module;
        $project = $module->project;
        $path = "$project->id";
        if(Storage::exists($path)){
            $shell_script = '';
        }
    }

    public function index(Request $request){
        $project_id = $request->get('project_id');
        $project = Project::find($project_id);
//        dd($project);
        if(!$project){
            return response()->error(1002);
        }

        $data = Project::where('id',$project_id)->with(['modules'=>function($query){
            $query->with(['apis'=>function($query){
                $query->with('markdown');
            }]);
        }])->first();
//        dd($data);
        return view('markdownDoc',['data'=>$data]);
    }
}
