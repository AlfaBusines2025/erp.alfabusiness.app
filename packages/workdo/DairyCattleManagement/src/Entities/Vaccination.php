<?php

namespace Workdo\DairyCattleManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vaccination extends Model
{
    use HasFactory;

    protected $table = 'vaccinations';

    protected $fillable = [
        'animal_id',
        'vaccination_name',
        'vaccination_date',
        'next_due_date',
        'veterinarian',
        'notes',
    ];
}
