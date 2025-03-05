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
        if (!Schema::hasTable('animal_milks'))
        {
            Schema::create('animal_milks', function (Blueprint $table) {
                $table->id();
                $table->integer('animal_id')->nullable();
                $table->date('date')->nullable();
                $table->integer('morning_milk')->nullable();
                $table->integer('evening_milk')->nullable();
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
        Schema::dropIfExists('animal_milks');
    }
};
