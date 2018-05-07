<?php

namespace App\Http\Controllers;

use App\Model\Project;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->id();
        $projects = Project::where('User_id',$user_id)->get();
        return view('home',compact('projects'));
    }
}
