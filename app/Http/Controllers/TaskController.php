<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use App\Comment;
use Auth;
use Illuminate\Http\Request;
use Response;
use Validator;
use App\Events\Auth\TaskUser;

class TaskController extends Controller
{
    public function index()
    {
        $all_tasks = Task::select('tasks.*','users.*')
        ->join('users','users.email','=','tasks.assigned_to')
        ->orderBy('tasks.sort_id','ASC')
        ->where('deleted_at',null)
        ->where('created_by',Auth::user()->email)->get();

        return response::json([
            'all_tasks' => $all_tasks,
        ]); 
    }


    public function store(Request $request)
    {
        $user = User::where('email',$request->assigned_to)->first();
        $input = $request->all();
        
        $request->validate([
            'title' => 'required',
            'assigned_to' => 'required',
        ]);
            
        $task = new Task;
        $task->fill($input);
       
        if ($task->save()){
            $task->sort_id = $task->task_id;
            $task->save();
        }

        
        event(new TaskUser($user));

        return response::json([
            'task' => $task,
            'user' => $user,
        ]);

       
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $user = User::where('email',$request->assigned_to)->first();
        $input = $request->all();
        
        $request->validate([
            'title' => 'required',
            'assigned_to' => 'required',
        ]);

        
        $task = Task::find($id);
        $task->fill($input);
        $task->update();

        
        event(new TaskUser($user));

        return response::json([
            'task' => $task,
        ]);
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete = 'deleted';
        $task->update();
        $task->delete();
        $comments = Comment::where('comment_task_id',$id)->get();
        foreach ($comments as $comment) {
            $comment->delete();
        }

        return response::json([
            'task' => $task,
        ]);
    }

    public function sort_task(Request $request)
    {
           $task = Task::find($request->taskId);
           $task->status = $request->id;
           $task->update();

            $position = 0;
            foreach ($request->ids as $id){
                $task1 = Task::where('task_id',$id)->update(['sort_id' => $position]);
                $position++;
            }

            return response::json([
                'id' => $request->id,
                'taskId' => $request->taskId,
                'task_status' => $task->status,  
            ]);
    }

}
