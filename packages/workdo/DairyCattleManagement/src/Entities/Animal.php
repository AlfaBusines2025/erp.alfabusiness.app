<?php

namespace Workdo\DairyCattleManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Animal extends Model
{
    use HasFactory;

    protected $fillable = ['name','species','breed','birth_date','gender','health_status','weight','breeding','note','image','workspace','created_by',];

    protected static function newFactory()
    {
        return \Workdo\DairyCattleManagement\Database\factories\AnimalFactory::new();
    }
    public static $healthstatus = [
        'Healthy',
        'Sick',
        'Injured',
    ];
    public static $breedingstatus = [
        'Ready For Breeding',
        'Pregnant',
        'Not Ready',
    ];
    public function animalmilk()
    {
        return $this->hasMany(AnimalMilk::class, 'animal_id', 'id');
    }


}
