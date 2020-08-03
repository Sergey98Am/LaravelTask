<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{

    use SoftDeletes;

    protected $primaryKey = 'comment_id';

    protected $fillable = [
        'comment_name','comment','user_email', 'comment_task_id'
    ];
}
