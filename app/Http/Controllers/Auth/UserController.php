<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function show(){}

    public function userInfo(){
        $user_info = auth()->user();
        return response()->success($user_info);
    }
}
