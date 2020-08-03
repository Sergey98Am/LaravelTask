<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use App\Tlist;
use Auth;
use Illuminate\Http\Request;
use Response;
use Validator;

class ReceivedTaskController extends Controller
{
    public function ReceivedTasks(){
        $all_tasks = Task::select('tasks.*','users.*','tlists.*')
            ->join('users','users.email','=','tasks.created_by')
            ->join('tlists','tlists.list_id','=','tasks.status')
            ->orderBy('tasks.sort_id','ASC')
            ->where('delete',null)
            ->where('assigned_to',Auth::user()->email)->get();
        $all_lists = Tlist::all();
        $users = User::all();
        
        return view('received_tasks',compact('all_tasks','all_lists','users'));
    }
}
