<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('animals', function (Blueprint $table) {
            // Agregar campo para nombre del propietario
            $table->string('nombre_propietario_animal')->nullable()->after('name');

            // Agregar campo para notas de dieta
            $table->string('notas_dieta_animal')->nullable()->after('nombre_propietario_animal');

            // Agregar campo para número de pesebrera
            $table->integer('numero_pesebrera_animal')->nullable()->after('notas_dieta_animal');

            // Agregar campo para instalaciones de pesebrera con opciones predefinidas
            $table->enum('instalaciones_pesebrera_animal', [
                'VERDES',
                'CAFES',
                'ESCUELITA',
                'PREMIUM',
                'POTREROS',
                'PORTATILES'
            ])->nullable()->after('numero_pesebrera_animal');
        });
    }

    public function down()
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropColumn([
                'nombre_propietario_animal',
                'notas_dieta_animal',
                'numero_pesebrera_animal',
                'instalaciones_pesebrera_animal'
            ]);
        });
    }
};
