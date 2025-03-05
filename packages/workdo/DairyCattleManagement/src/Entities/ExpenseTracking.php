<?php

namespace Workdo\DairyCattleManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseTracking extends Model
{
    use HasFactory;
    protected $table = 'dairy_expense_trackings';

    protected $fillable = [
        'category',
        'amount',
        'description',
        'expense_date'
    ];
}
