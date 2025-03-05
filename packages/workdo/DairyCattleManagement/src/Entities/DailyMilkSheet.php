<?php

namespace Workdo\DairyCattleManagement\Entities;

use Workdo\DairyCattleManagement\Entities\AnimalMilk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyMilkSheet extends Model
{
    use HasFactory;

    protected $fillable = ['id','animal_id','start_date','end_date','morning_milk','evening_milk','workspace','created_by'];

    protected static function newFactory()
    {
        return \Workdo\DairyCattleManagement\Database\factories\DailyMilkSheetFactory::new();
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }
    public function milks()
    {
        return $this->hasMany(AnimalMilk::class, 'animal_id', 'animal_id')
                ->whereBetween('date', [$this->start_date, $this->end_date]);
    }
    public function getAmTotal()
    {

        $subTotal = 0;
        foreach ($this->milks as $milk) {
            $subTotal += $milk->morning_milk;
        }
        return $subTotal;
    }

    public function getPmTotal()
    {

        $subTotal = 0;
        foreach ($this->milks as $milk) {
            $subTotal += $milk->evening_milk;
        }
        return $subTotal;
    }
    public function getTotal()
    {

        $total = 0;
        foreach ($this->milks as $milk) {
            $total = $this->getAmTotal() + $this->getPmTotal();

        }
        return $total;
    }
}
