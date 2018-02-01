<?php

namespace App\Http\Controllers\Host;

use App\Model\Host;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HostController extends Controller
{
    public function update($id,Request $request){
       $host = Host::find($id);
       if(!$host){
           return   response()->error(1003);
       }
       $host->headers = $request->get('headers');
       $res = $host->save();
       if($res){
           return response()->success('success');
       }else{
           return   response()->error(1003);
       }
    }
}
