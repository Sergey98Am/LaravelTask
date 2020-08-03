<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'task_id';

    protected $fillable = [
        'task_name','title', 'created_by', 'desc','status','assigned_to'
    ];

}
