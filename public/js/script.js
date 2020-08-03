$(document).ready(function () {

    //Sortable
    function sortableLists() {
        $('#sortable-div .row').sortable({
            stop: function () {
                var result = $.map($('.dragdrop'), function (n) {
                    return n.attributes[1].value;

                });
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "http://laravel_task.loc/sort_list",
                    method: "POST",
                    data: {
                        ids: result
                    },
                    beforeSend: function () {
                        $('#wrap').addClass('wrap');
                    },
                    success: function (data) {
                        $('#wrap').removeClass('wrap');
                    }
                })
            }
        })
    }
    sortableLists()


    function sortableTasks() {

        $('#sortable-div .sortable-list').sortable({
            connectWith: '#sortable-div .sortable-list',

            stop: function (e, ui) {
                var taskId = ui.item.val()
                var result = ui.item.closest('.dragdrop').data('tlist_id');

                var result_sort = $.map($('.sortable-item'), function (n) {
                    return n.attributes[1].value;
                });


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: 'http://laravel_task.loc/sort_task',
                    method: "POST",
                    data: {
                        id: result,
                        ids: result_sort,
                        taskId: taskId,
                    },
                    beforeSend: function () {
                        $('#wrap').addClass('wrap');
                    },
                    success: function (data) {
                        $('#wrap').removeClass('wrap');
                        console.log(data)
                    }
                })
            },

        })
    }
    sortableTasks()
    //end Sortable

    //View All Lists
    function allLists(response) {
        var row_all_lists = '';

        $.each(response.all_lists, (key, value) => {
            row_all_lists = row_all_lists +
                `<div id="dragdrop" data-tlist_id="${value.list_id}" class="dragdrop col-sm-4 col-xs-4 col-md-4">
                    <div class="well clearfix m-1">
                        <div class="header mb-3" style="display: flex">
                            <span style="font-size: 20px;margin-right: 10px"><b>${value.list_title}</b></span>
                            <div class="update_delete">
                                <button style="margin-right: 5px" type="button" class="btn btn-primary" data-toggle="modal" 
                                data-target="#update_list_modal" data-list_id='${value.list_id}' 
                                data-list_title='${value.list_title}' data-list_created_by='${value.list_created_by}'>
                                    <i class="fa fa-edit"></i>
                                </button>
                                <form  id="delete_list" action="http://laravel_task.loc/list/${value.list_id}" method="post">
                                    <button class="btn btn-danger "><i class="fa fa-remove"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="header1">
                            <button id="add_task_btn" data-list_id="${value.list_id}" type="button" data-toggle="modal" data-target="#add_task_modal" class="add_task_btn btn btn-warning mb-2"><i class="fa fa-plus"></i> Add Task</button>
                        </div>
                        <div class="dragbleList">
                            <ul class="sortable-list">

                            </ul>
                        </div>
                </div>
            </div>`
        })

        return row_all_lists;
    }
    //end View All Lists

    //View All Tasks
    function sentTasksAll(value, response) {
        var row_all_tasks = '';

        $.each(response.all_tasks, (key1, value1) => {
            if (value1.status == $(value).find('.add_task_btn').data('list_id')) {
                row_all_tasks = row_all_tasks +
                    `<li class="sortable-item" id="taskId" name="taskId" value="${value1.task_id}">${value1.title}
                        <div class="update_delete">
                        <form class="comments_view" action="http://laravel_task.loc/comments_view/${value1.task_id}" method="get">
                        <button data-toggle="modal" data-target="#create_comment_modal"
                            class="update_delete_task" data-comment_task_id='${value1.task_id}'
                            data-user_email='${value1.assigned_to}'><i
                                class="fa fa-comment"></i></button>
                        </form>
                            <button type="button" data-toggle="modal" data-target="#update_task_modal"
                            class="update_delete_task" data-task_id='${value1.task_id}' data-title='${value1.title}' 
                            data-desc='${value1.desc}' data-assigned_to='${value1.assigned_to}'>
                            <i class="fa fa-edit"></i>
                            </button>
                            <form id="delete_task" action="http://laravel_task.loc/task/${value1.task_id}" method="post">
                                <button class="update_delete_task" style="color:red">
                                <i class="fa fa-remove"></i>
                                </button>
                            </form>
                        </div>
                    </li>`
            }
        })


        return row_all_tasks;
    }
    //end View All Tasks

    //Tlist CRUD

    //Create List
    $('#create_list').on('submit', function (e) {
        e.preventDefault()
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'post',
            data: form.serialize(),
            dataType: 'json',
            beforeSend: function () {
                $('#wrap').addClass('wrap');
                form.find('.help-block').detach()
            },
            success: function (response) {
                $('#wrap').removeClass('wrap');
                $('#create_list .modal').modal('hide');

                $.ajax({
                    url: 'http://laravel_task.loc/list/',
                    method: 'get',
                    data: form.serialize(),
                    beforeSend: function () {
                        $('#wrap').addClass('wrap');
                    },
                    success: function (response) {
                        $('#wrap').removeClass('wrap');
                        $('#sortable-div .row').html(allLists(response))
                        sortableLists()
                        sortableTasks()
                        $.ajax({
                            url: 'http://laravel_task.loc/task/',
                            method: 'get',
                            beforeSend: function () {
                                $('#wrap').addClass('wrap');
                            },
                            success: function (response) {
                                $('#wrap').removeClass('wrap');
        
                                $.each($(".dragdrop"), (key, value) => {
                                    $(value).find('.sortable-list').html(sentTasksAll(value, response));
                                })
        
                            }
                        })
                    }
                })


            },
            error: function (xhr) {
                $('#wrap').removeClass('wrap');
                var response = xhr.responseJSON;
                var errors = response.errors;

                $.each(errors, (key, value) => {
                    $(`#create_list #${key}`).addClass('input_border').closest(
                        '.form_div').append(
                        `<span class="help-block">${value.join(", ")}</span>`
                    )
                })
            }
        })

    })
    //end Create List


    //Update 

    //Update Modal Send Values
    $('#update_list .modal').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var list_id = button.data('list_id');
        var list_title = button.data('list_title');
        var list_created_by = button.data('list_created_by');

        var modal = $(this);
        // modal.find('.modal-body #task_id').val(task_id);
        modal.find('.modal-body #list_title').val(list_title);
        modal.find('.modal-body #list_created_by').val(list_created_by);
        $(this).parent().attr('action', 'http://laravel_task.loc/list/' + list_id)
    })
    //end Update Modal Send Values

    //Update List
    $('#update_list').on('submit', function (e) {
        e.preventDefault()
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'post',
            data: form.serialize(),
            dataType: 'json',
            beforeSend: function () {
                $('#wrap').addClass('wrap');
                form.find('.help-block').detach();
            },
            success: function (response) {
                $('#wrap').removeClass('wrap');
                $('#update_list .modal').modal('hide');


                $.ajax({
                    url: 'http://laravel_task.loc/list/',
                    method: 'get',
                    data: form.serialize(),
                    beforeSend: function () {
                        $('#wrap').addClass('wrap');
                    },
                    success: function (response) {
                        $('#wrap').removeClass('wrap');
                        $('#sortable-div .row').html(allLists(response))
                        sortableLists()
                        sortableTasks()
                        $.ajax({
                            url: 'http://laravel_task.loc/task/',
                            method: 'get',
                            beforeSend: function () {
                                $('#wrap').addClass('wrap');
                            },
                            success: function (response) {
                                $('#wrap').removeClass('wrap');
        
                                $.each($(".dragdrop"), (key, value) => {
                                    $(value).find('.sortable-list').html(sentTasksAll(value, response));
                                })
        
                            }
                        })
                    }
                })
             

            },
            error: function (xhr) {
                $('#wrap').removeClass('wrap');
                var response = xhr.responseJSON;
                var errors = response.errors;


                $.each(errors, (key, value) => {
                    $(`#update_task #${key}`).addClass('input_border').closest(
                        '.form_div').append(
                        `<span class="help-block">${value.join(", ")}</span>`
                    )

                })
            }
        })
    })
    //end Update List

    //Delete List
    $(document).on('submit', '#delete_list', function (e) {
        e.preventDefault()
        var form = $(this);
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            url: form.attr('action'),
            method: 'DELETE',
            data: {
                "_token": token,
            },
            dataType: 'json',
            beforeSend: function () {
                $('#wrap').addClass('wrap');
            },
            success: function (response) {
                $('#wrap').removeClass('wrap');
                $.ajax({
                    url: 'http://laravel_task.loc/list/',
                    method: 'get',
                    data: form.serialize(),
                    beforeSend: function () {
                        $('#wrap').addClass('wrap');
                    },
                    success: function (response) {
                        $('#wrap').removeClass('wrap');
                        $('#sortable-div .row').html(allLists(response))
                        sortableLists()
                        sortableTasks()
                        $.ajax({
                            url: 'http://laravel_task.loc/task/',
                            method: 'get',
        
                            beforeSend: function () {
                                $('#wrap').addClass('wrap');
                            },
                            success: function (response) {
                                $('#wrap').removeClass('wrap');
        
                                $.each($(".dragdrop"), (key, value) => {
                                    $(value).find('.sortable-list').html(sentTasksAll(value, response));
                                })
        
                            }
                        })
                    }
                })
                
            },
            error: function () {
                $('#wrap').removeClass('wrap');
            }

        })
    })
    //end Delete List

    //end Tlist CRUD


    //Task CRUD

    //Create Task
    $('#create_task').on('submit', function (e) {
        e.preventDefault()
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'post',
            data: form.serialize(),
            dataType: 'json',
            beforeSend: function () {
                $('#wrap').addClass('wrap');
                form.find('.help-block').detach()
            },
            success: function (response) {
                console.log(response)
                $('#wrap').removeClass('wrap');
                $('#create_task .modal').modal('hide');

                $.ajax({
                    url: 'http://laravel_task.loc/task/',
                    method: 'get',

                    beforeSend: function () {
                        $('#wrap').addClass('wrap');
                    },
                    success: function (response) {
                        $('#wrap').removeClass('wrap');

                        $.each($(".dragdrop"), (key, value) => {
                            $(value).find('.sortable-list').html(sentTasksAll(value, response));
                        })

                    }
                })

            },
            error: function (xhr) {
                $('#wrap').removeClass('wrap');
                var response = xhr.responseJSON;
                var errors = response.errors;

                $.each(errors, (key, value) => {
                    $(`#create_task #${key}`).addClass('input_border').closest(
                        '.form_div').append(
                        `<span class="help-block">${value.join(", ")}</span>`
                    )
                })
            }
        })

    })
    //end Create Task


    //Create Task Modal Send Values1
    $('#create_task .modal').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var list_id = button.data('list_id');


        var modal = $(this);
        modal.find('.modal-body #status').val(list_id);

    })
    //end Create Task Modal Send Values1


    //Update Modal Send Values
    $('#update_task .modal').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var task_id = button.data('task_id');
        var title = button.data('title');
        var desc = button.data('desc');
        var assigned_to = button.data('assigned_to');

        var modal = $(this);
        modal.find('.modal-body #task_id').val(task_id);
        modal.find('.modal-body #title').val(title);
        modal.find('.modal-body #desc').val(desc);
        modal.find('.modal-body #assigned_to').val(assigned_to);
        $(this).parent().attr('action', 'http://laravel_task.loc/task/' + task_id)
    })
    //end Update Modal Send Values


    //Update Task
    $('#update_task').on('submit', function (e) {
        e.preventDefault()
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'post',
            data: form.serialize(),
            dataType: 'json',
            beforeSend: function () {
                $('#wrap').addClass('wrap');
                form.find('.help-block').detach();
            },
            success: function (response) {
                $('#wrap').removeClass('wrap');
                $('#update_task .modal').modal('hide');

                $.ajax({
                    url: 'http://laravel_task.loc/task/',
                    method: 'get',

                    beforeSend: function () {
                        $('#wrap').addClass('wrap');
                    },
                    success: function (response) {
                        $('#wrap').removeClass('wrap');

                        $.each($(".dragdrop"), (key, value) => {
                            $(value).find('.sortable-list').html(sentTasksAll(value, response));
                        })

                    }
                })

            },
            error: function (xhr) {
                $('#wrap').removeClass('wrap');
                var response = xhr.responseJSON;
                var errors = response.errors;


                $.each(errors, (key, value) => {
                    $(`#update_task #${key}`).addClass('input_border').closest(
                        '.form_div').append(
                        `<span class="help-block">${value.join(", ")}</span>`
                    )

                })
            }
        })
    })
    //end Update Task


    //Delete List
    $(document).on('submit', '#delete_task', function (e) {
        e.preventDefault()
        var form = $(this);
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            url: form.attr('action'),
            method: 'DELETE',
            data: {
                "_token": token,
            },
            dataType: 'json',
            beforeSend: function () {
                $('#wrap').addClass('wrap');
            },
            success: function (response) {
                $('#wrap').removeClass('wrap');
                $.ajax({
                    url: 'http://laravel_task.loc/task/',
                    method: 'get',

                    beforeSend: function () {
                        $('#wrap').addClass('wrap');
                    },
                    success: function (response) {
                        $('#wrap').removeClass('wrap');

                        $.each($(".dragdrop"), (key, value) => {
                            $(value).find('.sortable-list').html(sentTasksAll(value, response));
                        })

                    }
                })
            },
            error: function () {
                $('#wrap').removeClass('wrap');
            }

        })
    })
    //end Delete List


    //Change status
    $('.accordion .update_status').on('submit', function (e) {
        e.preventDefault()
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'post',
            data: form.serialize(),
            beforeSend: function () {
                $('#wrap').addClass('wrap');
            },
            success: function () {
                $('#wrap').removeClass('wrap');
                form.find('.btn-success').html('Change Status <i class="fa fa-check"></i>')

            },
            error: function (xhr) {
                $('#wrap').removeClass('wrap');

            }
        })
    })
    //end Change status

    //Comments
    //All Comments (each)
    function allComments(response) {
        var all_comments = '';
        $.each(response, (key, value) => {
            all_comments = all_comments +
                `<div class="card mb-2">
                <div class="card-body">
                   <div class="name_comment">
                    <b>${value.comment_name}: </b>
                    <span>${value.comment}</span>
                   </div>
                    <form  id="delete_comment" action="http://laravel_task.loc/delete_comment/${value.comment_id}" method="post">
                        <button class="update_delete_comment" style="color:red"><i class="fa fa-remove"></i></button>
                    </form>
                </div>
            </div>`
        })

        return all_comments;
    }
    // All Comments (each)

    //Comments View
    $(document).on('submit', '.comments_view', function (e) {
        e.preventDefault()

        var form = $(this);

        $.ajax({
            url: form.attr('action'),
            method: 'get',
            data: form.serialize(),
            beforeSend: function () {
                $('#wrap').addClass('wrap');
            },
            success: function (response) {
                $('#wrap').removeClass('wrap');
                $('.all_comments').html(allComments(response));


                console.log(response)
            }
        })
    })
    //Comments View

    //Comment Modal Send Values
    $('#create_comment_modal').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var comment_task_id = button.data('comment_task_id');
        var user_email = button.data('user_email');

        var modal = $(this);
        modal.find('.modal-body #comment_task_id').val(comment_task_id);
        modal.find('.modal-body #user_email').val(user_email);
        $(this).find('.create_comment').attr('action', 'http://laravel_task.loc/comment/' + comment_task_id)
    })
    //end Comment Modal Send Values

    //Comment Modal Send Values(Received Tasks)
    $('#r_create_comment_modal').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var comment_task_id = button.data('comment_task_id');
        var user_email = button.data('user_email');

        var modal = $(this);
        modal.find('.modal-body #comment_task_id').val(comment_task_id);
        modal.find('.modal-body #user_email').val(user_email);
        $(this).find('.create_comment').attr('action', 'http://laravel_task.loc/comment/' + comment_task_id)
    })
    //end Comment Modal Send Values(Received Tasks)


    //Create Comment
    $('.create_comment').on('submit', function (e) {
        e.preventDefault()
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'post',
            data: form.serialize(),
            beforeSend: function () {
                $('#wrap').addClass('wrap');
                form.find('.help-block').detach()
            },
            success: function (response) {
                $('#wrap').removeClass('wrap');
                var url = response.comment_task_id;
                $.ajax({
                    url: 'http://laravel_task.loc/comments_view/' + url,
                    method: 'get',

                    beforeSend: function () {
                        $('#wrap').addClass('wrap');
                    },
                    success: function (response) {
                        $('#wrap').removeClass('wrap');
                        $('.all_comments').html(allComments(response));
                    }
                })

            },
            error: function (xhr) {
                $('#wrap').removeClass('wrap');
                var response = xhr.responseJSON;
                var errors = response.errors;

                $.each(errors, (key, value) => {
                    $(`#create_comment #${key}`).addClass('input_border').closest(
                        '.form_div').append(
                        `<span class="help-block">${value.join(", ")}</span>`
                    )
                })
            }
        })

    })
    //end Create Comment


    //Delete Comment
    $(document).on('submit', '#delete_comment', function (e) {
        e.preventDefault()
        var form = $(this);
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            url: form.attr('action'),
            method: 'post',
            data: {
                "_token": token,
            },
            dataType: 'json',
            beforeSend: function () {
                $('#wrap').addClass('wrap');
            },
            success: function (response) {
                $('#wrap').removeClass('wrap');


                var url = response.comment_task_id;
                $.ajax({
                    url: 'http://laravel_task.loc/comments_view/' + url,
                    method: 'get',

                    beforeSend: function () {
                        $('#wrap').addClass('wrap');
                    },
                    success: function (response) {
                        $('#wrap').removeClass('wrap');
                        $('.all_comments').html(allComments(response));
                    }
                })
            },
            error: function () {
                $('#wrap').removeClass('wrap');
            }

        })
    })
    //end Delete Comment
    //end Comments

    //Reset Modal
    function resetModal() {
        $('body').on('hidden.bs.modal', '.modal', function (e) {

            $.each($('.modal_f'), (key, value) => {
                $(value).parent()[0].reset();
            })

            $.each($('.create_comment'), (key, value) => {
                $(value)[0].reset();
            })

            $.each($('.form_div .form-control'), (key, value) => {
                $(value).removeClass('input_border').removeClass('input_is_not_empty');
            })

            $(".help-block").remove();

        })
    }
    resetModal()
    //end Reset Modal

    //ModalScroll
    function modalScroll() {
        $('.modal').on('show.bs.modal', function (e) {
            $('body').removeClass('offcanvas-menu');

        })

        $('.modal').on("hidden.bs.modal", function (e) {
            if ($('.modal:visible').length) {
                $('body').addClass('modal-open');
            }
        })
    }
    modalScroll()
    //end ModalScroll

})