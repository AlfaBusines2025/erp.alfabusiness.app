<?php

namespace Workdo\DairyCattleManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MilkInventory extends Model
{
    use HasFactory;

    protected $fillable = ['daily_milksheet_id','date','workspace','created_by'];

    protected static function newFactory()
    {
        return \Workdo\DairyCattleManagement\Database\factories\MilkInventoryFactory::new();
    }

    public function dailyMilkSheet()
    {
        return $this->belongsTo(DailyMilkSheet::class, 'daily_milksheet_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(MilkProduct::class, 'product_id');
    }
}
