<?php

namespace Workdo\ToDo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TodoStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'complete',
        'order',
        'workspace_id',
        'created_by',
    ];
}
