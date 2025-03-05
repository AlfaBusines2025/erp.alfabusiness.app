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
        if (!Schema::hasTable('healths'))
        {
            Schema::create('healths', function (Blueprint $table) {
                $table->id();
                $table->integer('animal_id')->nullable();
                $table->string('veterinarian')->nullable();
                $table->integer('duration')->nullable();
                $table->date('date')->nullable();
                $table->date('checkup_date')->nullable();
                $table->string('diagnosis')->nullable();
                $table->string('treatment')->nullable();
                $table->integer('price')->nullable();
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
        Schema::dropIfExists('healths');
    }
};
