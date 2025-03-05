<?php

namespace Workdo\DairyCattleManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Breeding extends Model
{
    use HasFactory;

    protected $fillable = ['animal_id','breeding_date','gestation','due_date','breeding_status','note','workspace','created_by'];

    protected static function newFactory()
    {
        return \Workdo\DairyCattleManagement\Database\factories\BreedingFactory::new();
    }
    public static $breedingstatus = [
        'Pregnant',
        'Not Pregnant',
        'Heat',
    ];
}
