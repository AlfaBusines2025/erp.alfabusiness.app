<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('milk_products'))
        {
                Schema::create('milk_products', function (Blueprint $table) {
                    $table->id();
                    $table->integer('milk_inventory_id')->nullable();
                    $table->string('name')->nullable();
                    $table->string('responsible')->nullable();
                    $table->integer('sale_price')->nullable();
                    $table->integer('cost')->nullable();
                    $table->integer('quantity_on_hand')->nullable();
                    $table->integer('forcasted_quantity')->nullable();
                    $table->integer('workspace')->nullable();
                    $table->integer('created_by')->default('0');
                    $table->timestamps();
                });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('milk_products');
    }
};
