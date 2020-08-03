<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use App\Tlist;
use Auth;
use Illuminate\Http\Request;
use Response;
use Validator;

class SentTaskController extends Controller
{
    public function SentTasks(){
        $all_tasks = Task::select('tasks.*','users.*')
            ->join('users','users.email','=','tasks.assigned_to')
            ->orderBy('tasks.sort_id','ASC')
            ->where('deleted_at',null)
            ->where('created_by',Auth::user()->email)->get();
        $all_lists = Tlist::select('tlists.*','users.*')
            ->join('users','users.email','=','tlists.list_created_by')
            ->orderBy('tlists.sort_id','ASC')
            ->where('deleted_at',null)
            ->where('list_created_by',Auth::user()->email)->get();
        $users = User::all();
            
        return view('sent_tasks',compact('all_tasks','all_lists','users'));
    }

}