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
        if (!Schema::hasTable('feed_schedules'))
        {
            Schema::create('feed_schedules', function (Blueprint $table) {
                $table->id();
                $table->string('animal_id')->default(0);
                $table->string('feed_type_id')->default(0);
                $table->integer('quantity')->nullable();
                $table->dateTime('scheduled_time');
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
        Schema::dropIfExists('feed_schedules');
    }
};
