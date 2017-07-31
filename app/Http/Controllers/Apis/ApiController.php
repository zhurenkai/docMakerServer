<?php

namespace App\Http\Controllers\Apis;

use App\Model\Api;
use App\Model\CommonParam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function index()
    {
        return 1;
    }

    public function store(Request $request)
    {
        $files = $_FILES;
         echo  json_encode($request->all());

    }
}
