<?php

namespace Workdo\DairyCattleManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BirthRecord extends Model
{
    use HasFactory;
    protected $table = 'birth_records';

    protected $fillable = [
        'animal_id',
        'birth_date',
        'gender',
        'weight_at_birth',
        'health_status',
    ];

    public static $healthstatus = [
        'healthy'=>'Healthy',
        'sick'=>'Sick',
        'under observation'=>'Under Observation',
    ];
}
