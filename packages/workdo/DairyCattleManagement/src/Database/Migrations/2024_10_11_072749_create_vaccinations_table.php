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
        if (!Schema::hasTable('vaccinations'))
        {
            Schema::create('vaccinations', function (Blueprint $table) {
                $table->id();
                $table->string('animal_id')->default(0);
                $table->string('vaccination_name')->nullable();
                $table->date('vaccination_date');
                $table->date('next_due_date');
                $table->string('veterinarian')->nullable();
                $table->string('notes')->nullable();
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
        Schema::dropIfExists('vaccinations');
    }
};
