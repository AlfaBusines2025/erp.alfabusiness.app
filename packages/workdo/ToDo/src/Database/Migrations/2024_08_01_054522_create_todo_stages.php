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
        if (!Schema::hasTable('todo_stages')) {
            Schema::create('todo_stages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('color')->default('#051c4b');
                $table->boolean('complete');
                $table->integer('order');
                $table->unsignedBigInteger('workspace_id');
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
        Schema::dropIfExists('todo_stages');
    }
};
