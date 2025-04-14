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
        if (!Schema::hasTable('todo_module_lists')) {
            Schema::create('todo_module_lists', function (Blueprint $table) {
                $table->id();
                $table->string('module')->nullable();
                $table->string('sub_module')->nullable();
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todo_module_lists');
    }
};
