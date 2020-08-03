@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 text-center">
            <h1 style="color: white;font-size: 55px"><i>Laravel Task</i></h3>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 text-center">
            <h1 style="color: white;font-size: 40px"><i>Received Tasks</i></h3>
                <div class="accordion" id="accordionExample">
                    @foreach($all_tasks as $task)
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-block text-left" type="button" data-toggle="collapse"
                                    data-target="#Collapse{{ $task->task_id }}" aria-expanded="true"
                                    aria-controls="collapseOne">
                                    <b>Task Title: </b>{{ $task->title }}
                                    <b>Created By: </b>{{ $task->name }}
                                </button>
                            </h2>
                        </div>
                        <div id="Collapse{{ $task->task_id }}" class="collapse" aria-labelledby="headingOne"
                            data-parent="#accordionExample">
                            <div class="card-body" style="text-align: left">
                                <p class="card-text"> {{$task->desc}}</p>
                                <form class="update_status" id="update_status"
                                    action="{{ route('update_status',$task->task_id) }}" method="post">
                                    @csrf
                                    <label>Status</label><br>
                                    <select class="mb-2" name="status">
                                        @foreach($all_lists as $list)
                                        @if($list->list_created_by == $task->created_by)
                                        @if($list->list_id == $task->status)
                                        <option value="{{ $list->list_id }}" selected>{{$list->list_title}}</option>
                                        @else
                                        <option value="{{ $list->list_id }}">{{$list->list_title}}</option>
                                        @endif
                                        @endif
                                        @endforeach
                                    </select><br>
                                    <button class="btn btn-success">Change Status</button>
                                </form>
                                <form class="comments_view" action="{{ route('comments_view', $task->task_id) }}"
                                    method="get">
                                    <button class="update_delete_task btn btn-warning" data-toggle="modal"
                                        data-target="#r_create_comment_modal"
                                        data-comment_task_id='{{ $task->task_id }}'
                                        data-user_email='{{ $task->created_by }}'><i class="fa fa-comment"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
        </div>
    </div>
</div>

<!-- Comment -->
<div class="modal fade" id="r_create_comment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="create_comment" id="create_comment" action="" method="post">
                    @csrf
                    <input type="hidden" id="user_email" name="user_email">
                    <input type="hidden" id="comment_name" name="comment_name" value="{{ Auth::user()->name }}">
                    <input type="hidden" id="comment_task_id" name="comment_task_id">

                    <div class="form-group form_div">
                        <label for="comment">Comment</label>
                        <textarea class="form-control" id="comment" name="comment" placeholder="Write Comment"
                            rows="3"></textarea>
                    </div>
                    <button class="mb-3 btn btn-primary">Add Comment</button>
                </form>
                <div class="all_comments">

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>
<!-- end Comment -->
@endsection