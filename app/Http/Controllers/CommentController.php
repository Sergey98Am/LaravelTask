<?php

namespace App\Http\Controllers;


use App\Task;
use App\User;
use App\Comment;
use Auth;
use Illuminate\Http\Request;
use Response;
use Validator;
use App\Events\Auth\WriteComment;

class CommentController extends Controller
{
    public function index($id){
        $all_comments = Comment::orderBy('created_at','DESC')
        ->where('deleted_at',null)
        ->where('comment_task_id',$id)->get();

        return response::json($all_comments); 
    }

    public function CreateComment(Request $request){
        $user = User::where('email',$request->user_email)->first();
        $input = $request->all();
        
        $request->validate([
            'comment' => 'required',
        ]);
            
        $comment = new Comment;
        $comment->fill($input);
       
        $comment->save();
        event(new WriteComment($user));

        return response::json($comment);
    }

    public function DeleteComment($id){
        $comment = Comment::find($id);
        $comment->delete();

        return response::json($comment);
    }
}
