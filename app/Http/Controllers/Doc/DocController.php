<?php

namespace App\Http\Controllers\Doc;

use App\Model\KeyStatement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DocController extends Controller
{


    public function generate(Request $request)
    {
        $query_params = $request->get('queryParam');
        $form_data = $request->get('formParam');
        $project_id = $request->get('project_id');
        $doc = [];

        foreach($query_params as $index=>$value){
            $info = $this->getStatement($value['key']);
            $info['required'] = $value['required'];
            $doc['params'][] = $info;
        }

        foreach($form_data as $index=>$value){
            $info = $this->getStatement($value['k']);
            $info['required'] = $value['required'];
            $doc['params'][] = $info;
        }

        $response_data = $request->get('response');
            if(is_array($response_data)){
                $doc['response'] = $this->resopnseStatements($response_data);
            }
        return response()->success($doc);
    }


    /**
     * 递归查询返回参数中的说明
     * @param $response_data
     * @param array $res
     * @return array
     */
    private function resopnseStatements($response_data, &$res = []){

        foreach ($response_data as $k=>$v){
            // 用key过滤重复的参数
            if(!key_exists($k,$res)){
                $res[$k] = $this->getStatement($k);
            }
            // 数组的情况
            if(is_array($v)) {
                // 索引数组的并且是多维数组（如果数组里面的值类型不一样，就该去反思，写的什么鬼接口）
                if (is_array($v[0]) && array_keys($v) == array_keys(array_keys($v))) {
                    $this->resopnseStatements($v[0], $res);
                }
                // 关联数组数组
                else {
                    $this->resopnseStatements($v, $res);
                }
            }
        }
        return array_values($res);
    }

    private function getStatement($key){
        $info = KeyStatement::where('key',$key)
            ->orderByRaw('user_id='.auth()->id().' desc')
//                等加上项目id再说
//                ->orderByRaw('project_id='.$project_id.' desc')
            ->orderByDesc('weight')
            ->get();
        $ret = [
            'key'=>$key,
            'statement'=>'',
            'statement_options'=>[],
            'type'=>'',
            'required'=>''
        ];
        if(!$info->isEmpty()) {
            $ret['statement'] = $info[0]->statement;
            $ret['statement_options'] = $info;
            $ret['type'] = $info[0]->type;
        }
            return $ret;
    }
}
