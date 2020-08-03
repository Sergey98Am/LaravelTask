<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use App\Tlist;
use Auth;
use Illuminate\Http\Request;
use Response;
use Validator;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
            return view('home');
    }
}
