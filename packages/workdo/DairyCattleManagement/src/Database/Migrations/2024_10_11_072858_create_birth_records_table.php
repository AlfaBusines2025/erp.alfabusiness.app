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
        if (!Schema::hasTable('birth_records'))
        {
            Schema::create('birth_records', function (Blueprint $table) {
                $table->id();
                $table->string('animal_id')->default(0);
                $table->date('birth_date');
                $table->enum('gender',['male','female'])->default('female');
                $table->string('weight_at_birth')->default(0);
                $table->enum('health_status',['healthy','sick','under observation'])->default('healthy');
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
        Schema::dropIfExists('birth_records');
    }
};
