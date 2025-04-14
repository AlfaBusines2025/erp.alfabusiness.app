<?php

namespace Workdo\ToDo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TodoActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','user_type', 'project_id','log_type','remark'
    ];
}
