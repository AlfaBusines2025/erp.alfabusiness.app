<?php

namespace Workdo\ToDo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TodoModuleList extends Model
{
    use HasFactory;

    protected $fillable = ['module','sub_module','status'];
}
