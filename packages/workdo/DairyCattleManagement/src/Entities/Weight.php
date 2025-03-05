<?php

namespace Workdo\DairyCattleManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Weight extends Model
{
    use HasFactory;

    protected $fillable = ['animal_id','date','age','weight','workspace','created_by'];

    protected static function newFactory()
    {
        return \Workdo\DairyCattleManagement\Database\factories\WeightFactory::new();
    }
}
