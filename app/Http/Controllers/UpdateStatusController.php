<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use App\Tlist;
use Auth;
use Illuminate\Http\Request;
use Response;
use Validator;

class UpdateStatusController extends Controller
{
    public function index(){
        $all_tasks = Task::select('tasks.*','users.*','tlists.*')
        ->join('users','users.email','=','tasks.created_by')
        ->join('tlists','tlists.list_id','=','tasks.status')
        ->orderBy('tasks.sort_id','ASC')
        ->where('delete',null)
        ->where('assigned_to',Auth::user()->email)->get();

        return response::json($all_tasks);
    }

    public function UpdateStatus(Request $request, $id){
        $task = Task::find($id);
        $task->status = $request->status;
        $task->update();

        return response::json($task);
    }
}
