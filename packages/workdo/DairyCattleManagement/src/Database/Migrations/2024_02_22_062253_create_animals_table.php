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

        if (!Schema::hasTable('animals'))
        {
            Schema::create('animals', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('species')->nullable();
                $table->string('breed')->nullable();
                $table->date('birth_date')->nullable();
                $table->string('gender')->nullable();
                $table->integer('health_status')->nullable();
                $table->integer('weight')->nullable();
                $table->integer('breeding')->nullable();
                $table->longText('note')->nullable();
                $table->string('image')->nullable();
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
        Schema::dropIfExists('animals');
    }
};
