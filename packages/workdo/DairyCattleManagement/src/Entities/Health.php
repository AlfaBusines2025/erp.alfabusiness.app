<?php

namespace Workdo\DairyCattleManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Health extends Model
{
    use HasFactory;

    protected $fillable = ['animal_id','veterinarian','duration','date','checkup_date','diagnosis','treatment','price','workspace','created_by'];

    protected static function newFactory()
    {
        return \Workdo\DairyCattleManagement\Database\factories\HealthFactory::new();
    }
    public static $duration = [
        '1'=>'Month',
        '12'=>'Year',
        '3'=>'Quarterly',
        '6'=>'Half Year',
    ];
}
