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
        if (!Schema::hasTable('dairy_expense_trackings'))
        {
            Schema::create('dairy_expense_trackings', function (Blueprint $table) {
                $table->id();
                $table->string('category')->nullable();
                $table->string('amount')->nullable();
                $table->string('description');
                $table->date('expense_date');
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
        Schema::dropIfExists('dairy_expense_trackings');
    }
};
