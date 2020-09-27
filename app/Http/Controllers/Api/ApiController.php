<?php

namespace App\Http\Controllers\Api;

use App\Model\Api;
use App\Model\Host;
use App\Model\Module;
use App\Model\Param;
use App\Repository\Api\Param as ParamRepo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function GuzzleHttp\Psr7\str;

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
        $search = compact('name', 'module_id', 'user_id');
        $curl_code = $request->get('curl_code');


        if (Api::where($search)->first()) {
            return response()->error(5003);
        }
        $search['description'] = $description ?? '';

        if ($curl_code) {
            $api_info = $this->processCurlCode($curl_code);
            $search['host'] = $api_info['host'];
            $search['path'] = $api_info['path'];
            $search['method'] = $api_info['method'];
            $search['json_input'] = $api_info['json_input'];
            $form_params = array_merge($api_info['form'],$api_info['url_encode_form']);
        }
        $res = Api::create($search);
        if(isset($form_params) && !empty($form_params)){
            array_walk($form_params,function(&$v)use($res){
                $v['api_id'] = $res->id;
            });
            Param::insert($form_params);
        }
        if (!$res) {
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
        array_walk($hosts, function (&$item) {
            return $item->headers = json_decode($item->headers) ?: [];
        });
        $api->hosts = $hosts;
        $api->params;
        $api->document = json_decode($api->document);
        return response()->success($api);

    }

    public function update(Request $request, $id)
    {
        $api = Api::find($id);
        if (!$api) {
            return response()->error(1001);
        }
        $path = $request->get('path');
        $method = $request->get('method');
        $params = $request->get('params');
        $host = $request->get('host');
        $json_input = $request->get('json_input');
        if (!$path) {
            return response()->error(2001);
        }
        if (!$host) {
            return response()->error(2002);
        }
        $api->path = $path;
        $api->method = $method;
        $api->json_input = $json_input;
        $api->host = $host;
        $api->save();
        list($update_params, $store_params) = array_separate($params, function ($v) {
            return isset($v['id']);
        });
        array_walk($update_params, function (&$value) {
            $value['required'] = $value['required'] ? 1 : 0;
            $value['is_use'] = $value['is_use'] ? 1 : 0;
        });
        $current_params = $api->params()->pluck('id')->toArray();
        $old_params = array_column($update_params, 'id');
        $delete_params = array_diff($current_params, $old_params);
        Param::destroy($delete_params);
        if (!empty($update_params)) {
            $this->param->updateMany($update_params, 'id');

        }
        if (!empty($store_params)) {
            Param::insert($store_params);
        }
        return response()->success('success');
    }

    private function processCurlCode($code)
    {
        $code = str_replace(PHP_EOL, ' ', $code);
        preg_match('/--request (\S*)/', $code, $method_res);
        $method = $method_res[1] ?? 'GET';
        preg_match('/--request ' . $method . ' \'(\S*)\'/', $code, $url_res);
        $url = $url_res[1] ?? '';
        $https = false;
        $url = str_replace('http://', '', $url);
        if (strpos($url, 'https://') !== false) {
            $https = true;
            $url = str_replace('https://', '', $url);
        }
        $url_ex = explode('/', $url);

        $host = ($https ? 'https://' : 'http://') . $url_ex[0];
        array_shift($url_ex);
        $path = '/' . implode('/', $url_ex);

        preg_match('/--data-raw \'(.*)\'/', $code, $json_res);
        $json_input = $json_res[1] ?? '';


        $form = $this->getBatchParam($code);
        $url_encode_form = $this->getBatchParam($code, 'data-urlencode');
        $header = $this->getBatchParam($code, 'header');

        return compact('method', 'host', 'path', 'json_input', 'form', 'url_encode_form', 'header');
    }

    private function getBatchParam($code, $type = 'form')
    {
        $code_ex = explode('\\', $code);
        return array_filter(array_map(function ($v) use ($type) {
            preg_match('/--' . $type . ' \'(.*)\'/', $v, $form_res);
            if (isset($form_res[1])) {
                $delimiter = '=';
                $type == 'header' && $delimiter = ':';
                $arr = explode($delimiter, $form_res[1]);
                return [
                    'name' => trim($arr[0]) ?? '',
                    'value' => trim($arr[1]) ?? '',
                ];
            }
        }, $code_ex), function ($v) {
            return $v;
        });
    }
}
