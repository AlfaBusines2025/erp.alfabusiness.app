<?php

namespace Workdo\ProductService\Entities;

use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\WarehouseProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon; // Para manejar fechas
use Workdo\DairyCattleManagement\Entities\FeedSchedule; // <-- Ajusta si tu modelo está en otro namespace

class ProductService extends Model
{
    use HasFactory;

	protected $table = 'product_services'; // si no es el default

    protected $fillable = [
        'name','sku','sale_price','purchase_price','tax_id','category_id','description',
        'type','icon','parent_id','sort_order','route','is_visible','quantity','permissions',
        'module','image','unit_id','sale_chartaccount_id','expense_chartaccount_id',
        'workspace_id','created_by'
    ];

    public static $product_type = [
        'product' => 'Products',
        'service' => 'Services',
        'parts'   => 'Parts',
    ];
	
	public function getCalculatedQuantityAttribute()
	{
		// 1. Stock base (lo que realmente hay en la BD)
		$baseStock = $this->getOriginal('quantity');

		// 2. Consumo programado diario (feed_schedules)
		$feedSchedules = FeedSchedule::where('feed_type_id', $this->id)
			->where('scheduled_time', '<=', Carbon::now())
			->get();

		$totalScheduledConsumed = 0;
		foreach ($feedSchedules as $schedule) {
			$start = Carbon::parse($schedule->scheduled_time);

			// Si existe consumption_end y la fecha actual es mayor que consumption_end, se usa consumption_end.
			// En caso contrario, se usa Carbon::now().
			if ($schedule->consumption_end && Carbon::now()->greaterThan(Carbon::parse($schedule->consumption_end))) {
				$end = Carbon::parse($schedule->consumption_end);
			} else {
				$end = Carbon::now();
			}

			// Se suma +1 para contar el día inicial
			$days = $start->diffInDays($end) + 1;
			$consumed = $days * $schedule->quantity;
			$totalScheduledConsumed += $consumed;
		}

		// 3. Consumo extra esporádico (feed_consumptions)
		$totalExtraConsumed = \Workdo\DairyCattleManagement\Entities\FeedConsumption::where('feed_type_id', $this->id)
			->where('consumption_date', '<=', Carbon::now())
			->sum('quantity_consumed');

		// 4. Consumo total y cantidad restante
		$totalConsumed = $totalScheduledConsumed + $totalExtraConsumed;
		$remaining = $baseStock - $totalConsumed;

		return max($remaining, 0);
	}


	
	
	public function getTotalConsumed()
	{
		// 1. Consumo programado diario (feed_schedules)
		$feedSchedules = FeedSchedule::where('feed_type_id', $this->id)
			->where('scheduled_time', '<=', Carbon::now())
			->get();

		$totalScheduledConsumed = 0;
		foreach ($feedSchedules as $schedule) {
			$start = Carbon::parse($schedule->scheduled_time);

			// Usar consumption_end solo si ya se superó; de lo contrario, la fecha actual
			if ($schedule->consumption_end && Carbon::now()->greaterThan(Carbon::parse($schedule->consumption_end))) {
				$end = Carbon::parse($schedule->consumption_end);
			} else {
				$end = Carbon::now();
			}

			// Se suma +1 para contar el día inicial
			$days = $start->diffInDays($end) + 1;
			$consumed = $days * $schedule->quantity;
			$totalScheduledConsumed += $consumed;
		}

		// 2. Consumo extra esporádico (feed_consumptions)
		$totalExtraConsumed = \Workdo\DairyCattleManagement\Entities\FeedConsumption::where('feed_type_id', $this->id)
			->where('consumption_date', '<=', Carbon::now())
			->sum('quantity_consumed');

		// 3. Consumo total
		$totalConsumed = $totalScheduledConsumed + $totalExtraConsumed;

		return $totalConsumed;
	}






    /*
    |--------------------------------------------------------------------------
    | RELACIONES (units, category, tax, etc.)
    |--------------------------------------------------------------------------
    */
    public function units()
    {
        return $this->belongsTo('Workdo\ProductService\Entities\Unit', 'unit_id');
    }

    public function categorys()
    {
        return $this->belongsTo('Workdo\ProductService\Entities\Category', 'category_id');
    }

    public function taxes()
    {
        return $this->hasOne('Workdo\ProductService\Entities\Tax', 'id', 'tax_id')->first();
    }

    public function unit()
    {
        return $this->hasOne('Workdo\ProductService\Entities\Unit', 'id', 'unit_id')->first();
    }

    public function category()
    {
        return $this->hasOne('Workdo\ProductService\Entities\Category', 'id', 'category_id');
    }

    /*
    |--------------------------------------------------------------------------
    | EJEMPLOS DE FUNCIONES DE POS, COMPRAS, ETC.
    |--------------------------------------------------------------------------
    */
    public function getTotalProductQuantity()
    {
        if(module_is_active('Pos'))
        {
            // ... lógica previa ...
        }
        return null;
    }

    public function isUser()
    {
        return $this->type === 'user' ? 1 : 0;
    }

    public function warehouseProduct($product_id,$warehouse_id)
    {
        if(module_is_active('Pos'))
        {
            // ...
        }
        return 0;
    }

    public static function addProductStock($product_id, $quantity, $type, $description, $type_id)
    {
        // ... ejemplo de StockReport ...
    }

    public function getProductQuantity()
    {
        if(module_is_active('Pos'))
        {
            // ... otra lógica ...
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODO PARA DESCONTAR STOCK DE MANERA EXPLÍCITA
    |--------------------------------------------------------------------------
    */
    public function discountStock($amount)
    {
        // Restar la cantidad
        // Si deseas usar 'setQuantityAttribute' que SUMA, le pasas un número negativo:
        // $this->quantity = -1 * $amount;
        // $this->save();

        // O si quitaste ese mutator, simplemente:
        $this->attributes['quantity'] = $this->attributes['quantity'] - $amount;
        $this->save();
    }

    /*
    |--------------------------------------------------------------------------
    | NUEVO MÉTODO: Calcular la CANTIDAD REAL considerando feed_schedules
    |--------------------------------------------------------------------------
    | Ejemplo: entre fecha inicial y fecha final, sumar lo programado y restárselo
    | al stock actual (lo que esté en la base de datos).
    */
    public function getRealBodegaQuantity($startDate = null, $endDate = null)
    {
        // Fecha inicial por defecto: el "origen" de inventario (o lo pones a 1970)
        if(is_null($startDate)) {
            $startDate = Carbon::parse('1970-01-01');
        }
        // Fecha final por defecto: ahora
        if(is_null($endDate)) {
            $endDate = Carbon::now();
        }

        // 1. Stock base (tal cual guardado en la DB), sin la resta de 10 ni sumas forzadas
        //    Si quieres omitir tu getQuantityAttribute, lo mejor es usar $this->getOriginal('quantity')
        $baseStock = $this->getOriginal('quantity');

        // 2. Sumar las cantidades programadas en feed_schedules para este producto,
        //    en el rango [$startDate, $endDate].
        $totalScheduled = FeedSchedule::where('feed_type_id', $this->id)
            ->whereBetween('scheduled_time', [$startDate, $endDate])
            ->sum('quantity');

        // 3. Calcular la cantidad real
        $realQuantity = $baseStock - $totalScheduled;

        // Evitar negativos (opcional)
        return max(0, $realQuantity);
    }

	
	// 1) Al obtener la cantidad, se resta el total consumido.
    public function getQuantityAttribute($value)
    {
		
        if (module_is_active('DairyCattleManagement')) {
            $consumed = $this->getTotalConsumed();
            return max($value - $consumed, 0);
        }
        return $value;
    }

    // Al asignar, asumimos que el valor recibido es el stock real (lo que se muestra)
	// Para almacenar el stock base, le sumamos el total consumido.
	public function setQuantityAttribute($value)
	{
		if (module_is_active('DairyCattleManagement')) {
			$consumed = $this->getTotalConsumed();
			// El stock base se calcula sumando el stock real deseado y lo consumido
			$this->attributes['quantity'] = $value + $consumed;
		} else {
			$this->attributes['quantity'] = $value;
		}
	}

    /*
    |--------------------------------------------------------------------------
    | MUTATORS (si decides mantenerlos, con aviso de RIESGO DE CONFUSIÓN)
    |--------------------------------------------------------------------------
    */

    // 1) Este getQuantityAttribute quita 10 siempre. 
    //    NO recomendado si deseas ver stock real, pues '5000' en DB se mostrará como '4990'.
    /*
    public function getQuantityAttribute($value)
    {
        if (module_is_active('DairyCattleManagement')) {
            return $value - 10;
        }
        return $value;
    }
    */

    // 2) Este setQuantityAttribute suma, en lugar de asignar. 
    //    Ojo: $product->quantity = 100 se convierte en 'stock actual + 100'
    /*
    public function setQuantityAttribute($value)
    {
        if (module_is_active('DairyCattleManagement')) {
            $currentQuantity = $this->getOriginal('quantity') ?? 0;
            $this->attributes['quantity'] = $currentQuantity + $value;
        } else {
            $this->attributes['quantity'] = $value;
        }
    }
    */
}
