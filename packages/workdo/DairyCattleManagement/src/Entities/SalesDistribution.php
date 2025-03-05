<?php

namespace Workdo\DairyCattleManagement\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesDistribution extends Model
{
    use HasFactory;
    protected $table = 'sales_distributions';

    protected $fillable = [
        'customer_id',
        'milk_product_id',
        'quantity',
        'total_price',
        'sale_date'
    ];
}
