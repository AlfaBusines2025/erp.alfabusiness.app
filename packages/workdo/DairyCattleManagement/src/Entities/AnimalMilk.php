<?php

namespace Workdo\DairyCattleManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnimalMilk extends Model
{
    use HasFactory;

    protected $fillable = ['animal_id','date','morning_milk','evening_milk','workspace','created_by'];

    protected static function newFactory()
    {
        return \Workdo\DairyCattleManagement\Database\factories\AnimalMilkFactory::new();
    }


}
