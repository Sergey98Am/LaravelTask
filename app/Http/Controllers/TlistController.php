<?php

namespace App\Http\Controllers;

use App\Tlist;
use App\Task;
use App\Comment;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Response;
use Validator;

class TlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_lists = Tlist::select('tlists.*','users.*')
        ->join('users','users.email','=','tlists.list_created_by')
        ->orderBy('tlists.sort_id','ASC')
        ->where('deleted_at',null)
        ->where('list_created_by',Auth::user()->email)->get();

        return response::json([
            'all_lists' => $all_lists,
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        
        $request->validate([
            'list_title' => 'required',
            'list_created_by' => 'required',
        ]);
            
        $list = new Tlist;
        $list->fill($input);
        $list->save();

        return response::json([
            'list' => $list,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tlist  $tlist
     * @return \Illuminate\Http\Response
     */
    public function show(Tlist $tlist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tlist  $tlist
     * @return \Illuminate\Http\Response
     */
    public function edit(Tlist $tlist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tlist  $tlist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        
        $request->validate([
            'list_title' => 'required',
            'list_created_by' => 'required',
        ]);

        
        $list = Tlist::find($id);
        $list->fill($input);
        $list->update();


        return response::json([
            'list' => $list,
        ]);
    }


    public function destroy($id)
    {
        $list = Tlist::find($id);
        $list->delete();
        $tasks = Task::where('status',$id)->get();
        foreach ($tasks as $task) {
            $comments = Comment::where('comment_task_id',$task->task_id)->get();
            foreach ($comments as $comment) {
                $comment->delete();
            }
            $task->delete();
        }

        return response::json([
            'list' => $list,
        ]);
    }

    public function sort_list(Request $request){
        $position = 0;
        foreach ($request->ids as $id){
            $task1 = Tlist::where('list_id',$id)->update(['sort_id' => $position]);
            $position++;
        }
    }
}
