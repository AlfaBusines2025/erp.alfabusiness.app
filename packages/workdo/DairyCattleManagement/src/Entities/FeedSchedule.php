<?php

namespace Workdo\DairyCattleManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeedSchedule extends Model
{
    use HasFactory;

    protected $table = 'feed_schedules';

    protected $fillable = [
        'animal_id',
        'feed_type_id',
        'quantity',
        'scheduled_time',
		'consumption_end',
    ];
	
	// Mutator para formatear la fecha de consumption_end
    public function setConsumptionEndAttribute($value)
    {
        if (!empty($value)) {
            // Reemplaza la "T" por un espacio
            $value = str_replace('T', ' ', $value);
            // Si no vienen los segundos, se agregan
            if (strlen($value) === 16) {
                $value .= ':00';
            }
        }
        $this->attributes['consumption_end'] = $value;
    }
}
