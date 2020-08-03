@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 text-center">
            <h1 style="color: white;font-size: 55px"><i>Laravel Task</i></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center mb-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_list_modal">
                Add List
            </button>
        </div>
        <div id="sortable-div" class="col-sm-12 col-xs-12 col-md-12 addSub">
            <div class="padLR10">
            </div>
            <div class="row">
                @foreach($all_lists as $list)
                <div id="dragdrop" data-tlist_id="{{ $list->list_id }}" class="dragdrop col-sm-4 col-xs-4 col-md-4">

                    <div class="well clearfix m-1">
                        <div class="header mb-3" style="display: flex">
                            <span style="font-size: 20px;margin-right: 10px"><b>{{ $list->list_title }}</b></span>
                            <div class="update_delete">
                                <button style="margin-right: 5px" type="button" class="btn btn-primary"
                                    data-toggle="modal" data-target="#update_list_modal"
                                    data-list_id='{{ $list->list_id }}' data-list_title='{{  $list->list_title }}'
                                    data-list_created_by='{{  $list->list_created_by }}'>
                                    <i class="fa fa-edit"></i>
                                </button>
                                <form id="delete_list" action="{{ route('list.destroy',$list->list_id) }}"
                                    method="post">
                                    <button class="btn btn-danger"><i class="fa fa-remove"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="header1">
                            <button id="add_task_btn" data-list_id="{{ $list->list_id }}" type="button"
                                data-toggle="modal" data-target="#add_task_modal"
                                class="add_task_btn btn btn-warning mb-2"><i class="fa fa-plus"></i> Add Task</button>
                        </div>
                        <div class="dragbleList">
                            <ul class="sortable-list">
                                @foreach($all_tasks as $task)
                                @if($task->status == $list->list_id)
                                <li class="sortable-item" data-sort_id="{{ $task->task_id }}" id="taskId" name="taskId"
                                    value="{{ $task->task_id }}">
                                    {{ $task->title }}
                                    <div class="update_delete">
                                        <form class="comments_view"
                                            action="{{ route('comments_view', $task->task_id) }}" method="get">
                                            <button data-toggle="modal" data-target="#create_comment_modal"
                                                class="update_delete_task" data-comment_task_id='{{ $task->task_id }}'
                                                data-user_email='{{ $task->assigned_to }}'><i
                                                    class="fa fa-comment"></i></button>
                                        </form>
                                        <button type="button" data-toggle="modal" data-target="#update_task_modal"
                                            class="update_delete_task" data-task_id='{{ $task->task_id }}'
                                            data-title='{{ $task->title }}' data-desc='{{ $task->desc }}'
                                            data-assigned_to='{{ $task->assigned_to }}'><i
                                                class="fa fa-edit"></i></button>
                                        <form id="delete_task" action="{{ route('task.destroy',$task->task_id) }}"
                                            method="post">
                                            <button class="update_delete_task" style="color:red"><i
                                                    class="fa fa-remove"></i></button>
                                        </form>
                                    </div>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal Windows -->

    <!-- Create List -->
    <form id="create_list" action="{{ route('list.store') }}" method="post">
        @csrf
        <div class="modal modal_f fade" id="add_list_modal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add List</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="list_created_by" name="list_created_by"
                            value="{{ Auth::user()->email }}">
                        <div class="form-group form_div">
                            <label for="list_title">Title</label>
                            <input type="text" class="form-control" id="list_title" name="list_title">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- end Create List -->

    <!-- Update List -->
    <form id="update_list" action="" method="post">
        @csrf
        @method("PUT")
        <div class="modal modal_f fade" id="update_list_modal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update List</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="list_created_by" name="list_created_by"
                            value="{{ Auth::user()->email }}">
                        <div class="form-group form_div">
                            <label for="list_title">Title</label>
                            <input type="text" class="form-control" id="list_title" name="list_title">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- end Update List -->


    <!-- Create Task -->
    <form id="create_task" action="{{ route('task.store') }}" method="post">
        @csrf
        <div class="modal modal_f fade" id="add_task_modal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="status" name="status">
                        <input type="hidden" id="created_by" name="created_by" value="{{ Auth::user()->email }}">
                        <div class="form-group form_div">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                        <div class="form-group form_div">
                            <label for="desc">Description</label>
                            <textarea rows="3" class="form-control" id="desc" name="desc"></textarea>
                        </div>
                        <div class="form-group form_div">
                            <label for="assigned_to">Assigned To</label>
                            <select class="form-control" id="assigned_to" name="assigned_to">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                @if($user->id != Auth::user()->id)
                                <option value="{{ $user->email }}">{{ $user->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button class="btn btn-warning">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- end Create Task -->

    <!-- Update Task -->
    <form id="update_task" action="" method="post">
        @csrf
        @method('PUT')
        <div class="modal fade" id="update_task_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Update Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group form_div">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                        <div class="form-group form_div">
                            <label for="desc">Description</label>
                            <textarea rows="3" class="form-control" id="desc" name="desc"></textarea>
                        </div>
                        <div class="form-group form_div">
                            <label for="assigned_to">Assigned To</label>
                            <select class="form-control" id="assigned_to" name="assigned_to">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                @if($user->id != Auth::user()->id)
                                <option value="{{ $user->email }}">{{ $user->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button class="btn btn-warning">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- end Update Task -->



    <!-- Comment -->

    <div class="modal fade" id="create_comment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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


    <!-- end Modal Windows -->
</div>
@endsection