<?php

namespace Workdo\DairyCattleManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeedConsumption extends Model
{
    use HasFactory;

    protected $table = 'feed_consumptions';

    protected $fillable = [
        'animal_id',
        'feed_type_id',
        'quantity_consumed',
        'consumption_date',
    ];
}
