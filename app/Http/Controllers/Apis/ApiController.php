<?php

namespace App\Http\Controllers\Apis;

use App\Model\Api;
use App\Model\CommonParam;
use Defuse\Crypto\Key;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\KeyStatement;

class ApiController extends Controller
{
    public function index()
    {
        return 1;
    }

    public function store(Request $request)
    {
        var_dump(Api::where('user_id', 1)->first());

    }

    public function destroy($id)
    {
        $api = Api::find($id);
        if (!$api) {
            return response()->error(1001);
        }else{
            $res = $api->delete();
            if ($res) {
                return response()->success($res);
            }else{
                return response()->error(1002);
            }
        }

    }
}
