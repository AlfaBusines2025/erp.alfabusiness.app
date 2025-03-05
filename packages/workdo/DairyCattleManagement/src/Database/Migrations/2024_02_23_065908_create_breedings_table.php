<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('breedings'))
        {
            Schema::create('breedings', function (Blueprint $table) {
                $table->id();
                $table->integer('animal_id')->nullable();
                $table->string('breeding_date')->nullable();
                $table->integer('gestation')->nullable();
                $table->date('due_date')->nullable();
                $table->integer('breeding_status')->nullable();
                $table->longText('note')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default('0');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('breedings');
    }
};
