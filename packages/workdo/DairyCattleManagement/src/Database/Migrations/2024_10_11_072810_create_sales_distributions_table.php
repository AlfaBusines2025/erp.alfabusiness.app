<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('sales_distributions'))
        {
            Schema::create('sales_distributions', function (Blueprint $table) {
                $table->id();
                $table->string('customer_id')->default(0);
                $table->string('milk_product_id')->default(0);
                $table->string('quantity');
                $table->string('total_price')->nullable();
                $table->date('sale_date');
                $table->integer('workspace')->default(0);
                $table->integer('created_by')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_distributions');
    }
};
