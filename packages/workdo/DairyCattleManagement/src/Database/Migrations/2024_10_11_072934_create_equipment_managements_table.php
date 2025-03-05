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
        if (!Schema::hasTable('equipment_managements'))
        {
            Schema::create('equipment_managements', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('description');
                $table->date('purchase_date');
                $table->enum('maintenance_schedule',['monthly','quarterly','anually'])->default('monthly');
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
        Schema::dropIfExists('equipment_managements');
    }
};
