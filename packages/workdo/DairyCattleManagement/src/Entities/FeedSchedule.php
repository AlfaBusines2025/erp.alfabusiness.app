<?php

namespace Workdo\DairyCattleManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeedSchedule extends Model
{
    use HasFactory;

    protected $table = 'feed_schedules';

    protected $fillable = [
        'animal_id',
        'feed_type_id',
        'quantity',
        'scheduled_time',
    ];
}
