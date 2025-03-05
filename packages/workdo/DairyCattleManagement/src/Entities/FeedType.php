<?php

namespace Workdo\DairyCattleManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeedType extends Model
{
    use HasFactory;
    protected $table = 'feed_types';

    protected $fillable = [
        'name',
        'description'
    ];
}
