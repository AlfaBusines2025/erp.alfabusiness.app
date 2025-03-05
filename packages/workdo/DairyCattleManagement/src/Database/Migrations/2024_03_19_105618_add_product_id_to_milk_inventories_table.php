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
        if (Schema::hasTable('milk_inventories'))
        {
            Schema::table('milk_inventories', function (Blueprint $table) {
                $table->integer('product_id')->nullable()->after('date');
                $table->integer('grand_total')->nullable()->after('product_id');
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
        Schema::table('milk_inventories', function (Blueprint $table) {

        });
    }
};
