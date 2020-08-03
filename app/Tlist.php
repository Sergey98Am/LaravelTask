<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tlist extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'list_id';

    protected $fillable = [
        'list_title','list_created_by'
    ];
}
