<?php

namespace Workdo\DairyCattleManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EquipmentManage extends Model
{
    use HasFactory;
    protected $table = 'equipment_managements';

    protected $fillable = [
        'name',
        'description',
        'purchase_date',
        'maintenance_schedule'
    ];

    public static $schedules = [
        'monthly' => 'Monthly',
        'quarterly' => 'Quarterly',
        'anually' => 'Anually',
    ];
}
